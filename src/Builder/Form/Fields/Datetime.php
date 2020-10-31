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


    /**
     * @param  null  $value
     * @param  Field|null  $dateField
     * @return string
     */
    public function updateValue($value = null, ?Field $dateField = null)
    {
        return Carbon::createFromTimestamp($value)->format(Carbon::DEFAULT_TO_STRING_FORMAT);
    }
}
