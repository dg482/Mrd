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
     * Валидаторы
     *
     * @param  null  $request
     * @return array
     */
    public function getValidators($request = null): array
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
     * @param  string  $rule
     * @param  string|null  $message
     * @param  string|null  $idx
     * @return $this
     */
    public function addValidators(string $rule, ?string $message = '', ?string $idx = ''): Field
    {
        $_rule = explode(':', $rule);
        $idx = ($idx) ? $idx : current($_rule);

        $rule = [
            'idx' => $idx,
            'rule' => $rule,
            'trigger' => $this->trigger,
            'message' => '',
        ];

        if (!empty($message)) {
            $rule['message'] = $message;
        }

        if ($this->isMultiple()) {
            $rule['type'] = 'array';
        }

        switch ($idx) {
            case 'required':
                $rule['required'] = true;
                if (empty($rule['message'])) {
                    $rule['message'] = $this->trans('validation.'.$idx, ['attribute' => '"'.$this->getName().'"']);
                }
                break;
            case 'max':
            case 'size':
            case 'min':
                $setIdx = $idx === 'size' ? 'max' : $idx;
                if (isset($_rule[1])) {
                    $rule[$setIdx] = (int) $_rule[1];
                }
                $rule['type'] = $this->getFieldType();
                $rule['length'] = (int) $_rule[1];
                switch ($rule['type']) {
                    case Text::FIELD_TYPE:
                        if (empty($rule['message'])) {
                            $rule['message'] = $this->trans('validation.'.$setIdx.'.'.$rule['type'],
                                [
                                    'attribute' => '"'.$this->getName().'"',
                                    'max' => $rule[$setIdx],
                                ]);
                        }
                        break;
                    default:
                        break;
                }
                break;
            case 'in':
                $rule['type'] = 'enum';
                $rule['enum'] = array_map(function ($id) {
                    return (int) $id;
                }, explode(',', $_rule[1]));
                $rule['message'] = $this->trans('validation.'.$idx, ['attribute' => '"'.$this->getName().'"']);
                break;
            default:
                $rule['type'] = 'any';
                break;
        }
        array_push($this->validators, $rule);

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
