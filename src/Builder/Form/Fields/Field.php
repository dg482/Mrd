<?php

namespace Dg482\Mrd\Builder\Form\Fields;

use Dg482\Mrd\Builder\Form\AttributeTrait;
use Dg482\Mrd\Builder\Form\BadgeTrait;
use Dg482\Mrd\Builder\Form\Structure\BaseStructure;
use Dg482\Mrd\Builder\Form\TranslateTrait;
use Dg482\Mrd\Builder\Form\ValidatorsTrait;
use Dg482\Mrd\Model;

/**
 * Class Field
 * @package Dg482\Mrd\Builder\Form\fields
 */
abstract class Field
{
    use ValidatorsTrait, BadgeTrait, TranslateTrait, AttributeTrait;

    /** @var int|null $id */
    public ?int $id;

    /**
     * Имя поля
     * @var string
     */
    const NAME = 'name';

    /**
     * Код поля
     * @var string
     */
    const CODE = 'code';

    /**
     * Поле с указанием типа поля
     * @var string
     */
    const TYPE = 'type';

    /**
     * Идентификатор пользователя
     * @var string
     */
    const USER_ID = 'user_id';

    /** @var string */
    const DATA = 'data';

    /**
     * Тип поля по умолчанию
     * @var string
     */
    const FIELD_TYPE = '';

    /**
     * Хранилище по умолчанию бд
     * @var string
     */
    const STORAGE_BACKEND = 'backend';

    /**
     * Field label
     * @var string $name
     */
    protected string $name = '';

    /**
     * Model field
     * @var string $field
     */
    protected string $field = '';

    /**
     * Поле выключено?
     * @var bool $disabled
     */
    protected bool $disabled = false;

    /**
     * Хранилище значения поля
     * @var string
     */
    protected string $storage = self::STORAGE_BACKEND;

    /** @var string */
    protected string $placeholder = '';

    /** @var ?string */
    protected ?string $value = '';

    /** @var int */
    protected int $width = 100;

    /** @var bool */
    protected bool $multiple = false;

    /** @var array */
    protected array $data = [];

    /** @var Model|null */
    protected ?Model $relation;


    /**
     * Массив параметров поля для отрисовки в UI
     * @return array
     */
    public function getFormField(): array
    {
        $badge = $this->getBadge();
        $badges = $this->getBadges();

        return [
            'id' => empty($this->id) ? time() + rand(1, 99999) : $this->id,
            self::NAME => $this->getName(),
            self::TYPE => $this->getFieldType(),
            'field' => $this->getField(),
            'variant' => ($this instanceof FieldEnum) ? $this->getVariant() : [],
            'items' => ($this instanceof BaseStructure) ? $this->getItems() : [],
            'badge' => $badge !== null ? $badge->getData() : null,
            'badges' => ($badges !== []) ? array_map(function (Badge $badge) {
                return $badge->getData();
            }, $this->badges) : [],
            'disabled' => $this->isDisabled(),
            'storage' => $this->getStorage(),
            'attributes' => $this->getAttributes(),
            'placeholder' => $this->getPlaceholder(),
            'validators' => $this->getValidators(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     * @return $this
     */
    public function setName(string $name): Field
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldType(): string
    {
        return $this::FIELD_TYPE;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param  string  $field
     * @return $this
     */
    public function setField(string $field): Field
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param  bool  $disabled
     * @return $this|self
     */
    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getStorage(): string
    {
        return $this->storage;
    }

    /**
     * @param  string  $storage
     * @return Field
     */
    public function setStorage(string $storage): Field
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param  array  $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): Field
    {
        if (false === isset($attributes['showTable'])) {
            $attributes['showTable'] = true;
        }

        if (false === isset($attributes['showForm'])) {
            $attributes['showForm'] = true;
        }

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @param  string  $placeholder
     * @return Field
     */
    public function setPlaceholder(string $placeholder): Field
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Скрыть поле в форме
     * @return $this
     */
    public function hideForm(): Field
    {
        $this->attributes['showForm'] = false;

        return $this;
    }

    /**
     * Скрыть поле в таблице
     * @return $this
     */
    public function hideTable(): Field
    {
        $this->attributes['showTable'] = false;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setFieldValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * значение поля модели/сборное значение
     *
     * @param  bool  $original
     * @return ?string
     */
    public function getFieldValue(bool $original = false)
    {
        if ($original) {
            return $this->value;
        }

        return $this->value;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param  bool  $multiple
     * @return Field
     */
    public function setMultiple(bool $multiple): Field
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @param  Model  $model
     * @param  Model|null  $relation
     * @return $this
     */
    public function setFieldRelation(Model $model, ?Model $relation): Field
    {
        if (null === $this->relation && $relation) {
            $this->setData([
                'owner' => $model->{'id'},
                'form' => $_REQUEST['form'],
                'field' => $this->getField(),
                'relation' => $relation,
            ])->setFieldValue([]);

            if ($this instanceof FieldEnum) {
                $this->setVariant((new EnumVariant)
                    ->make($relation->{'id'}, $relation->{'name'}))
                    ->setFieldValue([$relation->{'id'}]);
            }

            $this->relation = $relation;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param  array  $data
     * @return Field
     */
    public function setData(array $data): Field
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Model|null
     */
    public function getFieldRelation()
    {
        return $this->relation;
    }

    /**
     * @param  string  $rule
     * @param  string|null  $message
     * @param  string|null  $idx
     * @return $this
     */
    public function addValidators(string $rule, ?string $message = '', ?string $idx = ''): Field
    {
        $resultRule = [
            'idx' => '',
            'rule' => $rule,
            'trigger' => $this->trigger,
            'message' => $message ?? '',
            'type' => ($this->isMultiple()) ? 'array' : $this->getFieldType(),
        ];

        $this->initRule($rule, $this->getName(), $resultRule);

        array_push($this->validators, $resultRule);

        return $this;
    }
}
