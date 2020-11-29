<?php

namespace Dg482\Mrd\Builder\Form\Fields;

/**
 * Class Select
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class Select extends FieldEnum
{


    const FIELD_TYPE = 'select';

    /**
     * @param  bool  $original
     * @return string
     */
    public function getFieldValue(bool $original = false)
    {
        if ($original) {
            return parent::getFieldValue();
        }

        $variant = array_filter($this->getVariant(), function (array $variant) {
            if ($this->isMultiple()) {
                return in_array((int) $variant['id'], (array)$this->value);
            }

            return (int) $variant['id'] === (int) $this->value;
        });
        if ($variant !== []) {
            return current($variant)['value'] ?? $this->value;
        }

        return parent::getFieldValue();
    }
}
