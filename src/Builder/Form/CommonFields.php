<?php


namespace Dg482\Mrd\Builder\Form;


use Dg482\Mrd\Builder\Form\Fields\Field;
use Dg482\Mrd\Builder\Form\Fields\Hidden;

/**
 * Trait CommonFields
 * @package Dg482\Mrd\Builder\Form
 */
trait CommonFields
{
    /**
     * @param  Field  $field
     * @return Field
     */
    public function fieldUserId(Field $field): Field
    {
        $user_id = $field->getFieldValue(true);
        if (empty($user_id)) {
            $field->setFieldValue(0);
        }

        return (new Hidden())
            ->setFieldValue($field->getFieldValue(true))
            ->hideTable();
    }
}
