<?php

namespace Dg482\Mrd\Builder\Form\Fields\Interfaces;

use Dg482\Mrd\Builder\Form\FormModelInterface;

/**
 * Interface FileStorageInterface
 * @package Dg482\Mrd\Builder\Form\Fields\Interfaces
 */
interface FileStorageInterface extends FormModelInterface
{
    /**
     * @return string
     */
    public function getPath(): string;
}
