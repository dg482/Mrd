<?php

namespace Dg482\Mrd\Builder\Form\Structure;

use Dg482\Mrd\Builder\Form\Fields\FieldTrait;

/**
 * Class Fieldset
 * @package Dg482\Mrd\Builder\Form\Structure
 */
class Fieldset extends BaseStructure
{


    const FIELD_TYPE = 'fieldset';

    public function setCssClass(string $class): self
    {
        $this->attributes['fieldset']['class'] = $class;
        $this->attributes['legend']['class'] = $class;

        return $this;
    }
}
