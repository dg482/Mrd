<?php

namespace Dg482\Mrd\Resource;

use Dg482\Mrd\Adapters\Adapter;
use Dg482\Mrd\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Mrd\Builder\Form\BaseForms;
use Dg482\Mrd\Builder\Form\CommonFields;
use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Structure\BaseStructure;
use Dg482\Mrd\Builder\Form\Structure\Fieldset;
use Dg482\Mrd\Builder\Form\ValidatorsTrait;
use Dg482\Mrd\Commands\Crud\Read;
use Dg482\Mrd\Model;
use Dg482\Mrd\Resource\Actions\Create as ActionCreate;
use Dg482\Mrd\Resource\Actions\Delete as ActionDelete;
use Dg482\Mrd\Resource\Actions\Update as ActionUpdate;

/**
 * Ресурс модели
 *
 * Ресурс предоставляет общие методы для отображения модели в виде таблиц.
 *
 * В $rowActions и $actions определяются доступные действия в таблицах.
 *
 * Для отображения ресурса в форме редактирования используются формы Dg482\Mrd\Builder\Form\BaseForms с
 * ссылкой на ресурс. Форма определяет методы для работы с полями, сохранение и обновление данных через CRUD команды.
 *
 * Отношения моделей (relations) определяются как самостоятельные ресурсы не имеющие свои формы унаследованные от
 * RelationsResource для HasMany и RelationResource для HasOne типа отношения.
 *
 * @package Dg482\Mrd\resource
 */
class Resource
{
    use ResourceTrait, RelationTrait, ValidatorsTrait, CommonFields;

    /**
     * Кол-во элементов на страницу
     * @var int
     */
    const PAGE_SIZE = 50;

    /**
     * Адаптер для работы с БД
     * @var AdapterInterfaces|Adapter
     */
    protected $adapter;

    /**
     * Команда адаптера
     * @var string
     */
    protected string $command = Read::class;

    /**
     * Текущая модель ресурса
     * @var Model|null
     */
    protected ?Model $model = null;

    /**
     * Текущее отношение в контексте ресурса
     * @var string|null
     */
    protected ?string $relation = null;

    /**
     * Список доступных отношений, ссылки на RelationResource
     * @var array
     */
    protected array $relations = [];

    /**
     * Модель формы
     * @var BaseForms
     */
    protected BaseForms $formModel;

    /**
     * @var string
     */
    protected string $context = '';

    /**
     * Список доступных действий для таблицы
     * @var string[]
     */
    protected array $actions = [
        ActionCreate::class,
        ActionUpdate::class,
        ActionDelete::class,
    ];

    /**
     * Список доступных действий для каждой записи таблицы
     * @var string[]
     */
    protected array $rowActions = [
        ActionUpdate::class,
        ActionDelete::class,
    ];

    /**
     * Массив со значениями полей модели
     * @var array
     */
    public array $values = [];

    /**
     * Поля, которые будут скрыты при построении списка полей
     * @var array $hidden_fields
     */
    protected array $hidden_fields = [];

    /**
     * Подписи к полям
     * @var array $labels
     */
    protected array $labels = [];

    /** @var array */
    protected array $tables = [];

    /**
     * Название ресурсы, используется в UI
     * @var string
     */
    protected string $title = '';


    /**
     * @return AdapterInterfaces
     */
    public function getAdapter(): AdapterInterfaces
    {
        return $this->adapter;
    }

    /**
     * @param  AdapterInterfaces  $adapter
     * @return Resource
     */
    public function setAdapter(AdapterInterfaces $adapter): self
    {
        $this->adapter = $adapter;

        $this->setModel($adapter->getModel());

        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return self::PAGE_SIZE;
    }

    /**
     * Поля формы ресурса
     * @return array
     */
    public function formFields(): array
    {
        $fields = [Fieldset::make('Empty fieldset', '')];

        if (method_exists($this->model, 'resourceFields')) {
            $fields = $this->model->resourceFields();
        }

        return $this->filterFields('isShowForm', $fields);
    }

    /**
     * Фильтр полей
     * @param  string  $method
     * @param  array  $fields
     * @return array
     */
    protected function filterFields(string $method, array $fields)
    {
        $original = (in_array($this->getContext(), [BaseForms::class, FormsResource::class]));

        return array_map(function (Field $field) use ($original) {

            if (false === empty($field->getField())) {
                // set model field value
                $this->values[$field->getField()] = $field->getFieldValue($original);

                if ([] !== $field->getValidators()) {
                    $this->validators[$field->getField()] = $field->getValidators();
                }
            }

            return $field->getFormField();
        }, array_filter($fields, function (Field $field) use ($method) {
            if ($field instanceof BaseStructure) {
                $field->setItems($this->filterFields($method, $field->getItems()));
            }

            return $field->{$method}();
        }));
    }

    /**
     * Фильтр коллекции
     * @param  array  $items
     * @return array
     */
    protected function filterItems(array $items): array
    {
        return array_filter($items, function ($item) {
            return $item;
        });
    }

    /**
     * Значения полей модели
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }


    /**
     * @return Model
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @param  Model  $model
     * @return self
     */
    protected function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param  BaseForms  $model
     * @return self
     */
    public function setFormModel(BaseForms $model): self
    {
        $this->formModel = $model;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param  null  $relation
     * @return Resource
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @param  Model  $model
     * @param  string|null  $relation
     * @return $this|self
     */
    public function make(Model $model, string $relation = null): self
    {
        $this->setModel($model);
        if ($relation) {
            $this->relation = $relation;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getHiddenFields(): array
    {
        return $this->hidden_fields;
    }

    /**
     * @param  array  $hidden_fields
     * @return self
     */
    public function setHiddenFields(array $hidden_fields): self
    {
        $this->hidden_fields = $hidden_fields;

        return $this;
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @param  array  $labels
     * @return self
     */
    public function setLabels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @param $values
     * @return $this
     */
    public function pushValues($values): self
    {
        array_push($this->values, $values);

        return $this;
    }

    /**
     * @param $values
     * @return $this
     */
    public function setValues($values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param  string[]  $actions
     * @return self
     */
    public function setActions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRowActions(): array
    {
        return $this->rowActions;
    }

    /**
     * @param  string[]  $rowActions
     * @return self
     */
    public function setRowActions(array $rowActions): self
    {
        $this->rowActions = $rowActions;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return BaseForms
     */
    public function getFormModel(): ?BaseForms
    {
        return $this->formModel;
    }

    /**
     * @return array
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @param  array  $relations
     * @return self
     */
    public function setRelations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param  string  $context
     * @return self
     */
    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }
}
