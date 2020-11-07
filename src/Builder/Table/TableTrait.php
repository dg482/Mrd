<?php

namespace Dg482\Mrd\Builder\Table;

use Dg482\Mrd\Adapters\Adapter;
use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Fields\Hidden;
use Dg482\Mrd\Model;
use Dg482\Mrd\Resource\Actions\ResourceAction;
use Dg482\Mrd\Resource\RelationResource;
use Dg482\Mrd\Resource\Resource;
use Exception;
use IteratorAggregate;

/**
 * Trait TableTrait
 * @package Dg482\Mrd\Builder\table
 */
trait TableTrait
{
    /**
     * Таблица
     *
     * @param  bool  $hardLoad
     * @return array
     * @throws Exception
     */
    public function getTable(bool $hardLoad = false): array
    {
        $this->setContext(Resource::class);

        $cls = get_class($this->getModel());

        if (isset($this->tables[$cls]) && $hardLoad === false) {
            return $this->tables[$cls];
        }

        if ($this instanceof RelationResource) {
            $collection = $this->getCollection();
        } else {
            /** @var Adapter $adapter */
            $adapter = $this->getAdapter();
            $adapter->setModel(new $this->model);

            /** @var array */
            $collection = $adapter->read($this->getPageSize());
        }

        $paginator = [
            'items' => $collection ?? [],
            'total' => ($collection) ? count($collection) : 1,
            'perPage' => self::PAGE_SIZE,
        ];

        // items table
        $items = [];

        $columns = $this->getFieldsTable();

        // columns table
        $setColumns = $this->getColumns();

        $relations = [];

        if ($this->relations) {
            array_map(function ($relation) use (&$setColumns, &$relations) {
                /** @var RelationResource $relation */
                $relation = (new $relation);
                $model = (new $relation)->getModel();
                if (!class_exists($model)) {
                    throw new Exception('relation Model not exist');
                }
                $relation->getAdapter()->setModel(new $model);

                if ($relation instanceof RelationResource) {
                    $relations[$relation->getRelationName()] = [];
                    $skipFields = ['id', 'action'];
                    $labels = $relation->getLabels();
                    array_map(function (Field $field) use ($relation, $skipFields, $labels, &$relations, &$setColumns) {
                        if ($field->isShowTable() && !in_array($field->getField(), $skipFields)
                            && (!$field instanceof Hidden)) {
                            $name = $relation->getRelationName().'|'.$field->getField();
                            // set relation labels
                            $this->labels[$name] = $labels[$field->getField()] ?? $field->getField();
                            $field->setField($name);

                            array_push($relations[$relation->getRelationName()], $field);

                            array_push($setColumns, $this->buildColumn($field->getField(), $field));
                        }
                    }, $relation->getFieldsTable());
                }
            }, $this->relations);
        }

        // колонка с действиями
        array_push($setColumns, [
            'width' => 100,
            'fixed' => 'right',
            'align' => 'center',
            'key' => 'action',
            'ellipsis' => true,
            'scopedSlots' => [
                'customRender' => 'action',
            ],
        ]);


        // формирование основных данных по модели
        array_map(function (Model $item) use (&$items, $columns, $relations) {
            $resultItems = ['_id' => $item->id];
            array_map(function (Field $field) use ($item, &$resultItems) {
                $id = $field->getField();
                if (strpos($id, '|') !== false) {
                    $id = explode('|', $id);
                    $id = end($id);
                    $field->setFieldValue($item->{$id});
                    $resultItems[$id] = $field->getFieldValue();
                } else {
                    $field->setFieldValue($item->{$field->getField()});
                    $resultItems[$field->getField()] = $field->getFieldValue();
                }
            }, $columns);

            //формирование данных по отношениям модели
            array_map(function ($fields, string $relation) use ($item, &$resultItems) {
                $relationModel = $item->{$relation};
                if ($relationModel) {
                    array_map(function (Field $field) use ($item, $relation, $relationModel, &$resultItems) {
                        $id = str_replace(['|', $relation], '', $field->getField());
                        if ($relationModel instanceof IteratorAggregate) {
                            $field->setFieldValue($relationModel);
                        } else {
                            $relationFieldMethod = $this->camel($id);

                            if (method_exists($relationModel, $relationFieldMethod)) {
                                $field->setFieldRelation($relationModel, $relationModel->{$relationFieldMethod});
                            }
                            $field->setFieldValue($relationModel->{$id});
                        }
                        $resultItems[$field->getField()] = $field->getFieldValue();
                    }, $fields);
                }
            }, $relations, array_keys($relations));

            array_push($items, $resultItems);
        }, $paginator['items']);

        $result = [
            'title' => $this->getTitle(),
            'columns' => $setColumns,
            'data' => $items,
            'actions' => $this->getActionList($this->getActions()),
            'rowActions' => $this->getActionList($this->getRowActions()),
            'pagination' => $this->getPagination($paginator),
        ];

        if ($this instanceof RelationResource) {
            $this->setValues($result);
        }

        $this->tables[$cls] = $result;

        return $result;
    }

    /**
     * @param  array  $actions
     * @return array
     */
    protected function getActionList(array $actions): array
    {
        $setActions = [];
        array_map(function (string $action) use (&$setActions) {
            /** @var ResourceAction $action */
            $action = (new $action);

            $formModel = $this->getFormModel();

            if ($formModel) {
                $action->setForm($formModel->getFormName());
            }

            array_push($setActions, [
                'id' => $action->getAction(),
                'text' => $action->getText(),
                'icon' => $action->getIcon(),
                'url' => $action->getActionUrl(),
                'form' => $action->getForm(),
                'confirm' => ($action->isConfirm()) ? $action->getConfirm() : false,
            ]);
        }, $actions);

        return $setActions;
    }

    /**
     * @param $paginator
     * @return array
     */
    protected function getPagination($paginator)
    {
        $paginator['perPage'] = $paginator['perPage'] ?? self::PAGE_SIZE;

        return [
            'showSizeChanger' => true,
            'total' => $paginator['total'] ?? 0,
            'currentPage' => $paginator['current'] ?? 1,
            'last' => ($paginator['total'] > 0) ? ceil($paginator['total'] / $paginator['perPage']) : 1,
            'perPage' => $paginator['perPage'],
        ];
    }

    /**
     * @param $id
     * @param $field
     * @return array
     */
    protected function buildColumn($id, $field): array
    {
        $column = [
            'id' => $field->id ?? $id,
            'key' => $field->id ?? $id,
            Field::NAME => $field->{Field::NAME},
            Field::TYPE => $field->getFieldType(),
            'dataIndex' => $id,
            'ellipsis' => true,
            'width' => $id === 'id' ? 80 : 200,
            'title' => (isset($this->labels[$id])) ? $this->labels[$id] : $id,
        ];

        switch ($column[Field::TYPE]) {
            case 'file':
                $column['scopedSlots'] = [
                    'customRender' => 'file',
                ];
                $column['width'];
                break;
            default:
                break;
        }

        return $column;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        // columns table
        $setColumns = [];

        foreach ($this->getFieldsTable() as $id => $field) {
            array_push($setColumns, $this->buildColumn($id, $field));
        }

        return $setColumns;
    }

    /**
     * @return array
     */
    public function getFieldsTable(): array
    {
        return array_filter($this->fields(), function (Field $field) {
            return $field->isShowTable();
        });
    }
}
