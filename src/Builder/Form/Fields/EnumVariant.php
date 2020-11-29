<?php

namespace Dg482\Mrd\Builder\Form\Fields;

/**
 * Class EnumVariant
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class EnumVariant
{
    /** @var int $id */
    protected int $id;

    /** @var string */
    protected string $name;

    /** @var string */
    protected string $model = 'field_enum_variant';

    public function __construct(int $id = 0, string $name = '')
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param  int  $id
     * @param  string  $name
     * @return $this
     */
    public function make(int $id = 0, string $name = '')
    {
        $this->id = $id;
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
