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
    /** @var null|Model */
    protected ?Model $model = null;

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
        $show = !in_array($column['id'], $this->hidden);

        switch ($column['id']) {
            case 'id':
            case 'created_at':
            case 'updated_at':
            case 'deleted_at':
                $field = new Hidden();
                break;
            default:
                switch ($column['type']) {
                    case Text::FIELD_TYPE:
                        $field = (new Text);
                        break;
                    case Integer::FIELD_TYPE:
                    case Integer::FIELD_TYPE_BIGINT:
                    case Integer::FIELD_TYPE_SMALLINT:
                        $field = (new Integer);
                        break;
                    case Date::FIELD_TYPE:
                        $field = (new Date);
                        break;
                    case Datetime::FIELD_TYPE:
                        $field = (new Datetime);
                        break;
                    case Boolean::FIELD_TYPE:
                        $field = (new Boolean);
                        break;
                    default:
                        throw new FieldNotFound(sprintf('Field type "%s" not found.', $column['type']));
                }
                break;
        }


        $field->setField($column['id'])
            ->setName($column['id'])
            ->showTable($show)
            ->showForm($show);

        if ($this->model && $this->model->{$column['id']}) {
            $field->setFieldValue($this->model->{$column['id']});
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
