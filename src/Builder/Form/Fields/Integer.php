<?php

namespace Dg482\Mrd\Builder\Form\Fields;

/**
 * Class Integer
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class Integer extends Field
{
    use FieldTrait;

    const FIELD_TYPE = 'integer';
    const FIELD_TYPE_BIGINT = 'bigint';
    const FIELD_TYPE_SMALLINT = 'smallint';


}
