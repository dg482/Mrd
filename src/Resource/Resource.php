<?php

namespace Dg482\Mrd\Resource;

use Dg482\Mrd\Adapters\Adapter;
use Dg482\Mrd\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Mrd\Builder\Form\BaseForms;
use Dg482\Mrd\Builder\Form\CommonFields;
use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\FormModelInterface;
use Dg482\Mrd\Builder\Form\Structure\BaseStructure;
use Dg482\Mrd\Builder\Form\Structure\Fieldset;
use Dg482\Mrd\Builder\Form\ValidatorsTrait;
use Dg482\Mrd\Commands\Crud\Read;
use Dg482\Mrd\Resource\Actions\Create as ActionCreate;
use Dg482\Mrd\Resource\Actions\Delete as ActionDelete;
use Dg482\Mrd\Resource\Actions\Update as ActionUpdate;
use Illuminate\Support\Collection;

/**
 * Ресурс модели
 *
 * Ресурс предоставляет общие методы для отображения моделей в табличном представление.
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
     * @var string
     */
    protected string $model;

    /**
     * Текущее отношение в контексте ресурса
     * @var null
     */
    protected ?string $relation = null;

    /**
     * Список доступных отношений, ссылки на RelationResource
     * @var array
     */
    protected array $relations = [];

    /**
     * Модель формы
     * @var string
     */
    protected string $formModel = '';

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
     * Массив с значениями полей модели
     * @var array
     */
    public array $values = [];

    /**
     * Поля которые будут скрыты при построение списка полей
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getAdapter(): AdapterInterfaces
    {
        if (null === $this->adapter) {
            $this->setAdapter(app()->make(EloquentAdapter::class));
        }

        return $this->adapter;
    }

    /**
     * @param  AdapterInterfaces  $adapter
     */
    public function setAdapter(AdapterInterfaces $adapter): void
    {
        $this->adapter = $adapter;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return request()->input('pageSize', self::PAGE_SIZE);
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
     *
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
                    $this->validators[$field->getField()]=$field->getValidators();
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
     *
     * @param  Collection  $items
     * @return Collection
     */
    protected function filterItems(Collection $items): Collection
    {
        return $items->filter(function ($item) {
            return $item;
        });
    }

    /**
     * Значения полей модели
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }


    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param  string  $model
     * @return Resource
     */
    public function setModel(string $model): Resource
    {
        $this->model = $model;

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
     * @param  string  $model
     * @param  string|null  $relation
     * @return $this|Resource
     */
    public function make(string $model, string $relation = null): Resource
    {
        $this->setModel($model);

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
     * @return Resource
     */
    public function setHiddenFields(array $hidden_fields): Resource
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
     * @return Resource
     */
    public function setLabels(array $labels): Resource
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
     * @return Resource
     */
    public function setActions(array $actions): Resource
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
     * @return Resource
     */
    public function setRowActions(array $rowActions): Resource
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
     * @return Resource
     */
    public function setTitle(string $title): Resource
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return FormModelInterface
     */
    public function getFormModel(): ?FormModelInterface
    {
        if (class_exists($this->formModel) && !$this->formModel instanceof FormModelInterface) {
            return new $this->formModel;
        }

        return null;
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
     * @return Resource
     */
    public function setRelations(array $relations): Resource
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
     * @return Resource
     */
    public function setContext(string $context): Resource
    {
        $this->context = $context;

        return $this;
    }

}
