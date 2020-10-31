<?php

namespace Dg482\Mrd\Builder\Form;

use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Structure\BaseStructure;
use Dg482\Mrd\Builder\Form\Structure\Fieldset;
use Dg482\Mrd\LocalCache;

/**
 * Trait FormTrait
 * @package Dg482\Mrd\Builder\Form
 */
trait FormTrait
{
    /**
     * @return array|array[]
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getForm(): array
    {
        return [
            'title' => $this->model->getFormTitle(),
            'form' => $this->model->getFormName(),
            'items' => $this->formFields(),
            'actions' => $this->model->getActions(),
            'values' => $this->getValues(),
            'validator' => $this->getValidators(),
            'context' => $this->getContext(),
        ];
    }

    /**
     * @param  array  $request
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getFormField(string $name): ?Field
    {
        $fields = [Fieldset::make('Empty fieldset', '')];

        if ($formFields = $this->_formFields()) {
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function _formFields()
    {
        return app()->make(LocalCache::class)
            ->withCache('schema-'.get_class($this->model), function () {
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getValidateFormRules(string $keySeparator = '|'): array
    {
        $result = ['rules' => [], 'messages' => [], 'attributes' => []];
        $request = request();

        if ($formFields = $this->_formFields()) {
            $key = $this->model->getFormName();

            array_map(function (Field $field) use (&$result, $request, $keySeparator) {

                $key = str_replace('.', $keySeparator, $field->getField());
                $result['rules'][$key] = [];

                array_map(function (array $rule) use (&$result, $key, $field) {
                    array_push($result['rules'][$key], $rule['rule']);
                    if (isset($rule['message']) && !empty($rule['message'])) {
                        $result['messages'][$key.'.'.$rule['idx']] = $rule['message'];
                    }
                }, $field->getValidators($request));

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
}
