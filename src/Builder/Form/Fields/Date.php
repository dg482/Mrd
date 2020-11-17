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
     * значение поля модели/сборное значение
     *
     * @param  bool  $original
     * @return string
     */
    public function getFieldValue(bool $original = false)
    {
        if ($original === false) {
            $value = Carbon::createFromFormat(Carbon::DEFAULT_TO_STRING_FORMAT, $this->value);

            return $value->format(Carbon::DEFAULT_TO_STRING_FORMAT);
        }

        return $this->value;
    }
}
