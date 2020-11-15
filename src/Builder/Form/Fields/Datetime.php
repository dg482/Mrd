<?php

namespace Dg482\Mrd\Builder\Form\Fields;

use Carbon\Carbon;

/**
 * Class Datetime
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class Datetime extends Field
{
    use FieldTrait;

    /** @var string */
    const FIELD_TYPE = 'datetime';
}
