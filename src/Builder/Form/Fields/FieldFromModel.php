<?php

namespace Dg482\Mrd\Builder\Form\Fields;

use Dg482\Mrd\Builder\Exceptions\FieldNotFound;
use Dg482\Mrd\Model;

/**
 * Class FieldFromModel
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class FieldFromModel
{
    /** @var Model */
    protected Model $model;

    /**
     * FieldFromModel constructor.
     * @param  Model  $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @var array
     */
    protected array $hidden = [
        'password',
    ];

    /**
     * @param  array  $column
     * @return Field
     * @throws FieldNotFound
     */
    public function getField(array $column): Field
    {
        $id = (string)$column['id'];
        $show = !in_array($id, $this->hidden);

        switch ($id) {
            case 'id':
            case 'created_at':
            case 'updated_at':
            case 'deleted_at':
                $field = new Hidden(0, $id, $this->model);

                break;
            default:
                switch ($column['type']) {
                    case Text::FIELD_TYPE:
                        $field = (new Text(0, $id, $this->model));
                        break;
                    case Integer::FIELD_TYPE:
                    case Integer::FIELD_TYPE_BIGINT:
                    case Integer::FIELD_TYPE_SMALLINT:
                        $field = (new Integer(0, $id, $this->model));
                        break;
                    case Date::FIELD_TYPE:
                        $field = (new Date(0, $id, $this->model));
                        break;
                    case Datetime::FIELD_TYPE:
                        $field = (new Datetime(0, $id, $this->model));
                        break;
                    case Boolean::FIELD_TYPE:
                        $field = (new Boolean(0, $id, $this->model));
                        break;
                    case File::FIELD_TYPE:
                        $field = (new File(0, $id, $this->model));
                        break;
                    default:
                        throw new FieldNotFound(sprintf('Field type "%s" not found.', (string)$column['type']));
                }
                break;
        }


        $field->setField($id)
            ->setName($id);

        $field->showTable($show);
        $field->showForm($show);

        if (isset($this->model->{$column['id']})) {
            $field->setFieldValue((string)$this->model->{$column['id']});
        }

        return $field;
    }

    /**
     * @return Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @param  Model|null  $model
     * @return FieldFromModel
     */
    public function setModel(?Model $model): FieldFromModel
    {
        $this->model = $model;

        return $this;
    }
}
