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
        if ($this->value instanceof Carbon && $original === false) {
            return $this->value->format(Carbon::DEFAULT_TO_STRING_FORMAT);
        }

        return $this->value;
    }
}
