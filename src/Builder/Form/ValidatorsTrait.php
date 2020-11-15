<?php

namespace Dg482\Mrd\Builder\Form;

use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Fields\Text;

/**
 * Trait ValidatorsTrait
 * @package Dg482\Mrd\Builder\Form
 */
trait ValidatorsTrait
{
    /**
     * Массив валидаторов
     * @var array
     */
    protected array $validators = [];

    /** @var array */
    protected array $error_messages = [];

    /** @var array 'blur' | 'change' | ['change', 'blur'] */
    protected array $trigger = ['blur', 'change'];

    /** @var bool */
    protected bool $required = false;

    /**
     * @return $this|Field
     */
    public function setRequired(): Field
    {
        $this->addValidators('required');

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
     * Валидаторы
     *
     * @return array
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * @param  array  $validators
     * @return $this
     */
    public function setValidators(array $validators): self
    {
        $this->validators = $validators;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->error_messages;
    }

    /**
     * @param  array  $error_messages
     * @return ValidatorsTrait
     */
    public function setErrorMessages(array $error_messages): ValidatorsTrait
    {
        $this->error_messages = $error_messages;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrigger(): string
    {
        return implode('|', $this->trigger);
    }

    /**
     * @param  string|array  $trigger
     * @return ValidatorsTrait
     */
    public function setTrigger(array $trigger): ValidatorsTrait
    {
        $this->trigger = $trigger;

        return $this;
    }
}
