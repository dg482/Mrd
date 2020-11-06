<?php

namespace Dg482\Mrd\Resource;


use Dg482\Mrd\Builder\Form\BaseForms;

/**
 * Class FormsResource
 * @package Dg482\Mrd\Resource
 */
class FormsResource extends Resource
{
    use ResourceTrait;

    /**
     * FormsResource constructor.
     * @param  string  $getFormClass
     */
    public function __construct(string $getFormClass)
    {
        $this->model = (new BaseForms)->setModel($getFormClass)->getForm();

    }
}
