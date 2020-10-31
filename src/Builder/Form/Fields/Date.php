<?php

namespace Dg482\Mrd\Builder\Form\Fields;

use Carbon\Carbon;

/**
 * Class Date
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class Date extends Datetime
{
    use FieldTrait;

    /** @var string */
    const FIELD_TYPE = 'date';

    /**
     * @param  null  $value
     * @param  Field|null  $dateField
     * @return string
     */
    public function updateValue($value = null, ?Field $dateField = null)
    {
        if (empty($value)) {
            return null;
        }

        if ($timestamp = strtotime($value)) {
            $value = $timestamp;
        }

        return Carbon::createFromTimestamp($value)->format('Y-m-d');
    }
}
