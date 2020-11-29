<?php

namespace Dg482\Mrd\Builder\Form;

use Dg482\Mrd\Builder\Form\Buttons\Button;
use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Fields\Hidden;
use Dg482\Mrd\Builder\Form\Fields\Select;
use Dg482\Mrd\Builder\Form\Fields\Table;
use Dg482\Mrd\Model;
use Dg482\Mrd\Resource\Resource as BaseResource;
use Exception;

/**
 * Class BaseForms
 * @package App\Models\Forms
 */
class BaseForms implements Model
{
    use ValidatorsTrait;

    /** @var string */
    const ACTION_SAVE = 'action_save';

    /** @var string */
    const ACTION_REPLICATE = 'action_replicate';

    /** @var string */
    const ACTION_CANCEL = 'action_cancel';

    /** @var string $title */
    public string $title = 'forms';

    /** @var string */
    protected string $formName = 'ui';

    /** @var Model $model */
    private Model $model;

    /** @var array */
    private array $actions = [];

    /**
     * @var BaseResource $resource
     */
    protected BaseResource $resource;

    /**
     * BaseForms constructor.
     * @param  Model  $model
     * @param  BaseResource  $resource
     */
    public function __construct(Model $model, BaseResource $resource)
    {
        $this->model = $model;
        $this->resource = $resource;
    }


    /**
     * @return mixed
     * @throws Exception
     */
    protected function fields()
    {
        $this->resource()->setContext(BaseForms::class);

        return $this->resource->fields();
    }

    /**
     * @return BaseResource
     */
    public function resource(): BaseResource
    {
        return $this->resource;
    }

    /**
     * @param  Model  $model
     * @return $this
     */
    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Model
     */
    public function getForm(): Model
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getFormTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getActions(): array
    {
        if ($this->actions === []) {
            array_push($this->actions, (new Button)
                ->make('Сохранить')
                ->setAction(self::ACTION_SAVE)
                ->setType('primary')
                ->setIcon('check'));
            array_push($this->actions, (new Button)
                ->make('Копировать')
                ->setAction(self::ACTION_REPLICATE)
                ->setIcon('copy'));

            array_push($this->actions, (new Button)
                ->make('Отменить')
                ->setAction(self::ACTION_CANCEL)
                ->setType('danger')
                ->setIcon('close'));
        }

        return $this->actions;
    }

    /**
     * @param  array  $actions
     * @return $this
     */
    public function setActions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormName(): string
    {
        return $this->formName;
    }

    /**
     * @param  string  $relation
     * @return array
     * @throws Exception
     */
    public function hasOneField(string $relation): array
    {
        return $this->resource->hasOne($relation)
            ->fields();
    }

    /**
     * @param  string  $relation
     * @param  string  $fieldType
     * @return Field
     * @throws Exception
     */
    public function hasManyField(string $relation, $fieldType = Table::FIELD_TYPE): Field
    {
        $relationResource = $this->resource->hasMany($relation);
        $relationResource->setField($fieldType);

        switch ($relationResource->getField()) {
            default:
            case Table::FIELD_TYPE:
            case Select::FIELD_TYPE:
                $field = new Select(0, $relation, $this->model);
//                TODO: add element to select field
//                array_map(function (Model $model) {
//                }, $relationResource->getCollection());
                break;
        }

        return $field;
    }

    /**
     * @param  array  $request
     * @return bool
     */
    public function update(array $request): bool
    {
        return false;
    }

    /**
     * @return array
     */
    public function getFillableFields(): array
    {
        return [];
    }

    /**
     * @param  array  $request
     * @return $this|Model
     */
    public function create(array $request): Model
    {
        return $this;
    }

    /**
     * @param  Field  $field
     * @return Field
     */
    public function fieldUserId(Field $field): Field
    {
        $user_id = $field->getFieldValue(true);
        if (empty($user_id)) {
            $field->setFieldValue('0');
        }

        return (new Hidden(0, (string)$field->getFieldValue(true), $this->getForm()))
            ->hideTable();
    }
}
