<?php

namespace Dg482\Mrd\Builder\Form\Fields;

/**
 * Class FieldEnum
 * @package Dg482\Mrd\Builder\Form\fields
 */
class FieldEnum extends Field
{
    /** @var array $variant */
    protected array $variant = [];

    /**
     * @var array[]
     */
    protected array $defaultVariant = [];

    /** @var bool */
    protected bool $multiple = false;

    /**
     * @return array
     */
    public function getVariant(): array
    {
        return $this->variant;
    }

    /**
     * @param  EnumVariant  $variant
     * @return $this
     */
    public function setVariant(EnumVariant $variant): self
    {
        array_push($this->variant, $variant->getValue());

        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultVariant(): self
    {
        $this->variant = $this->defaultVariant;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param  bool  $multiple
     * @return FieldEnum|Field
     */
    public function setMultiple(bool $multiple): Field
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @return $this|Field
     */
    public function initValidators(): Field
    {
        $rule = 'in:'.implode(',', array_map(function (array $variant) {
                return $variant['id'];
            }, $this->getVariant()));
        $this->addValidators($rule);

        return $this;
    }
}
