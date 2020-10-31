<?php


namespace Dg482\Mrd\Builder\Form\Fields;

/**
 * Class Switcher
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class Switcher extends FieldEnum
{
    use FieldTrait;

    const FIELD_TYPE = 'switch';

    /**
     * @var array[]
     */
    protected array $defaultVariant = [
        ['id' => 0, 'name' => 'нет'],
        ['id' => 1, 'name' => 'да']
    ];
}
