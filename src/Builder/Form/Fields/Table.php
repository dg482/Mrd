<?php

namespace Dg482\Mrd\Builder\Form\Fields;

/**
 * Class Table
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class Table extends Field
{
    use FieldTrait;

    const FIELD_TYPE = 'table';

    /**
     * @param $value
     * @return Table
     */
    public function setFieldValue($value): Field
    {
        $value = $value === [] ? [
            'columns'=>[],
            'data'=>[],
            'pagination' => [
                'total' => 0,
                'current' => 1,
                'last' => 1,
                'perPage' => 25,
            ],
        ] : $value;

        $this->value = $value;

        return $this;
    }
}
