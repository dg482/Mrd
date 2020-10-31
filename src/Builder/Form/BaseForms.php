<?php

namespace Dg482\Mrd\Builder\Form;

use Dg482\Mrd\Builder\Form\Buttons\Button;
use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Fields\Select;
use Dg482\Mrd\Builder\Form\Fields\Table;
use Dg482\Mrd\Resource\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class BaseForms
 * @package App\Models\Forms
 */
class BaseForms extends Model
{
    use ValidatorsTrait, CommonFields;

    /** @var string */
    const ACTION_SAVE = 'action_save';

    /** @var string */
    const ACTION_REPLICATE = 'action_replicate';

    /** @var string */
    const ACTION_CANCEL = 'action_cancel';

    /**
     * @var string $title
     */
    public string $title = 'forms';

    /** @var string */
    protected string $formName = 'ui';

    /** @var $model BaseForms */
    private $model;

    /**
     * @var string[]
     */
    private $actions = [];

    /**
     * @var string
     */
    protected string $resource;

    /**
     * @var mixed|Resource
     */
    protected $_resource;

    /**
     * Identity constructor.
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (class_exists($this->resource)) {
            $this->_resource = (new $this->resource);
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function fields()
    {
        $this->resource()->setContext(BaseForms::class);

        $fields = $this->_resource->fields();

        return $fields;
    }

    /**
     * @return Resource|null
     */
    public function resource(): ?Resource
    {
        return $this->_resource;
    }

    /**
     * @param  string  $getFormClass
     * @return $this
     */
    public function setModel(string $getFormClass): self
    {
        $getFormClass = 'App\Resources\\'.
            implode('\\', array_map(function ($segment) {
                return Str::studly($segment);
            }, explode('/',str_replace('/','/forms/', $getFormClass))));
        if (class_exists($getFormClass)) {
            $this->model = (new  $getFormClass());
        }

        return $this;
    }

    /**
     * @return BaseForms
     */
    public function getForm(): BaseForms
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
     * @return string[]
     */
    public function getActions(): array
    {
        if ($this->actions === []) {
            array_push($this->actions, (new Button)
                ->make('Сохранить')
                ->setAction(self::ACTION_SAVE)
//                ->setType('primary')
                ->setIcon('check'));
//            array_push($this->actions, (new Button)
//                ->make('Копировать')
//                ->setAction(self::ACTION_REPLICATE)
//                ->setIcon('copy'));
//
//            array_push($this->actions, (new Button)
//                ->make('Отменить')
//                ->setAction(self::ACTION_CANCEL)
//                ->setType('danger')
//                ->setIcon('close'));
        }

        return $this->actions;
    }

    /**
     * @param  string[]  $actions
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function hasOneField(string $relation): array
    {
        return $this->_resource->hasOne($relation)
            ->fields();
    }

    /**
     * @param  string  $relation
     * @param  string  $fieldType
     * @return Field
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function hasManyField(string $relation, $fieldType = Table::FIELD_TYPE): Field
    {
        $relationResource = $this->_resource->hasMany($relation);
        $relationResource->setField($fieldType);

        switch ($relationResource->getField()) {
            case Select::FIELD_TYPE:
                $field = Select::make($relation, $relation);

                array_map(function (Model $model) {

                }, $relationResource->getCollection());

                break;
            case Table::FIELD_TYPE:
            default:
                return (new Table)
                    ->setField($relation)
                    ->setFieldValue(
                        $relationResource->getRelation() === $relation ?
                            $relationResource->getTable() : []);
        }

        return $field;
    }
}
