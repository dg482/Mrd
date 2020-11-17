<?php

namespace Dg482\Mrd\Builder\Form;

use Dg482\Mrd\Builder\Exceptions\ModelNotInstalled;
use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Structure\BaseStructure;
use Dg482\Mrd\LocalCache;
use Exception;

/**
 * Trait FormTrait
 * @package Dg482\Mrd\Builder\Form
 */
trait FormTrait
{
    /**
     * @return array|array[]
     * @throws Exception
     */
    public function getForm(): array
    {
        if (null === $this->model) {
            throw new ModelNotInstalled;
        }

        return [
            'title' => $this->formModel->getFormTitle(),
            'form' => $this->formModel->getFormName(),
            'items' => $this->formFields(),
            'actions' => $this->formModel->getActions(),
            'values' => $this->getValues(),
            'validator' => $this->getValidators(),
            'context' => $this->getContext(),
        ];
    }

    /**
     * @param  array  $request
     * @return bool
     */
    public function updateForm(array $request): bool
    {
        return $this->getAdapter()->updateCmd($request, $this);
    }

    /**
     * Получение поля формы по имени
     *
     * @param  string  $name
     * @return Field
     */
    public function getFormField(string $name): ?Field
    {
//        $fields = [Fieldset::make('Empty fieldset', '')];

        if ($formFields = $this->formFields()) {
            $items = array_filter($formFields, function (Field $field) use ($name) {
                return $name === $field->getField();
            });
            if ($items !== []) {
                return current($items);
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    protected function formFields()
    {
        return $this->cache()->withCache('schema-'.get_class($this->model), function () {
            $formFields = [];
            $fields = [];
            if (method_exists($this->model, 'resourceFields')) {
                $fields = $this->model->resourceFields();
            }

            array_filter($fields, function ($field) use (&$formFields) {
                if ($field instanceof BaseStructure) {
                    array_map(function ($field) use (&$formFields) {
                        if ($field instanceof BaseStructure) {
                            $this->filter($field->getItems(), $formFields);
                        } else {
                            array_push($formFields, $field);
                        }
                    }, $field->getItems());
                } else {
                    array_push($formFields, $field);
                }
            });

            return $formFields;
        }, 'resource');
    }

    /**
     * @param  array  $items
     * @param $formFields
     * @return mixed
     */
    protected function filter(array $items, &$formFields)
    {
        array_map(function ($field) use (&$formFields) {
            if ($field instanceof BaseStructure) {
                $this->filter($field->getItems(), $formFields);
            } else {
                array_push($formFields, $field);
            }
        }, $items);

        return $formFields;
    }

    /**
     * @param  string  $keySeparator
     * @return array
     */
    public function getValidateFormRules(string $keySeparator = '|'): array
    {
        $result = ['rules' => [], 'messages' => [], 'attributes' => []];
        $request = $this->request();

        if ($formFields = $this->formFields()) {
//            $key = $this->model->getFormName();

            array_map(function (Field $field) use (&$result, $request, $keySeparator) {

                $key = str_replace('.', $keySeparator, $field->getField());
                $result['rules'][$key] = [];

                array_map(function (array $rule) use (&$result, $key, $field) {
                    array_push($result['rules'][$key], $rule['rule']);
                    if (isset($rule['message']) && !empty($rule['message'])) {
                        $result['messages'][$key.'.'.$rule['idx']] = $rule['message'];
                    }
                }, $field->getValidators());

                if ($result['rules'][$key] !== []) {
                    $result['attributes'][$key] = $field->getName();
                    $result['rules'][$key] = implode('|', $result['rules'][$key]);
                } else {
                    unset($result['rules'][$key]);
                }
            }, $formFields);
        }

        return $result;
    }

    /**
     * @param  string|null  $name
     * @param  string|int|array|null  $default
     * @return array|mixed|null
     */
    public function request(?string $name = null, $default = null)
    {
        if ($name) {
            return $_REQUEST[$name] ?? $default;
        }

        return $_REQUEST;
    }

    public function cache()
    {
        return (new LocalCache);
    }
}
