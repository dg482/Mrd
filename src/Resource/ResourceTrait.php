<?php

namespace Dg482\Mrd\Resource;

use Dg482\Mrd\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Mrd\Builder\Form\BaseForms;
use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Fields\FieldEnum;
use Dg482\Mrd\Builder\Form\Fields\File;
use Dg482\Mrd\Builder\Form\FormTrait;
use Dg482\Mrd\Builder\Table\TableTrait;
use Exception;

/**
 * Trait ResourceTrait
 * @package Dg482\Mrd\Resource
 */
trait ResourceTrait
{
    use TableTrait, FormTrait;

    /**
     * @param  AdapterInterfaces  $adapter
     * @param  string  $context
     * @return $this|Resource
     */
    public function initResource(AdapterInterfaces $adapter, string $context = ''): Resource
    {
        $adapter->setModel(new $this->model);
        $this->setAdapter($adapter);
        $this->setContext($context);

        return $this;
    }


    /**
     * @return Field[]
     * @throws Exception
     */
    public function fields(): array
    {
        $model = $this->getAdapter()->getModel();

        if (!$this->formModel) {
            throw new Exception('Form not exist!!!');
        }
        $validators = [];
        $error_message = [];

        $form = $this->formModel;

        if (method_exists($form, 'getValidators')) {
            $validators = $form->getValidators();
            if ($validators === [] && $form instanceof BaseForms) {
                $validators = $this->getValidators();
            }
        }

        if (method_exists($form, 'getErrorMessages')) {
            $error_message = $form->getErrorMessages();
        }

        $original = $this->getContext() === BaseForms::class;

        return $this->setForm(array_map(function (Field $field) use ($original, $form, $validators, $error_message) {


            $method = $this->camel('field_'.$field->getField());
            $key = $field->getField();

            if (method_exists($form, $method)) {
                /** @var Field $newField */
                $newField = $form->{$method}($field);
                $newField->setFieldValue($field->getFieldValue($original));

                $newField->setField($key);
                $newField->setName($field->getName());
                $newField->setAttributes($field->getAttributes());
                $field = $newField;
            }

            if ($this->getRelation()) {
                //set relation name
                $field->setField($this->getRelation().'|'.$key);

                if ($field instanceof File) {
                    $data = $field->getData();
                    $data['field'] = $key;
                    $field->setData($data);
                }
            }


            // init default validators
            if ($field instanceof FieldEnum && !$field instanceof File) {
                $field->initValidators();
            } else {
                if (!isset($validators[$key])) {
                    $validators[$key] = ['any'];
                }
            }

            if (isset($validators[$key])) {
                array_map(function (string $rule) use (&$field, $error_message, $key) {
                    $idx = explode(':', $rule);
                    $idx = current($idx);
                    if (isset($error_message[$key][$idx])) {
                        $field->addValidators($rule, $error_message[$key][$idx], $idx);
                    } else {
                        $field->addValidators($rule, null, $idx);
                    }
                }, $validators[$key]);
            }

            return $field;
        }, array_filter($this->adapter->getTableFields($model), function (Field $field) {
            $id = $field->getField();

            if (isset($this->labels[$id])) {
                $field->setName($this->labels[$id]);
            }

            return false === in_array($id, $this->hidden_fields);
        })));
    }

    /**
     * @param  array  $fields
     * @return array
     */
    protected function setForm(array $fields): array
    {
        return $fields;
    }

    /**
     * @param  string  $value
     * @return string
     */
    protected function camel(string $value): string
    {
        return lcfirst(str_replace(
            ' ',
            '',
            ucwords(str_replace(['-', '_'], ' ', $value))
        ));
    }
}
