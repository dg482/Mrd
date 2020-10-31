<?php

namespace Dg482\Mrd\Builder\Form\Buttons;

/**
 * Class Button
 * @package Dg482\Mrd\Builder\Form\Buttons
 */
class Button extends BaseButton
{
    /**
     * @param  string  $text
     * @return $this
     */
    public function make(string $text)
    {
        $this->setText($text);

        return $this;
    }
}
