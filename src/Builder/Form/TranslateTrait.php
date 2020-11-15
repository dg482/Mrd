<?php

namespace Dg482\Mrd\Builder\Form;

use Closure;

/**
 * Class TranslateTrait
 * @package Dg482\Mrd\Builder\Form
 */
trait TranslateTrait
{
    /** @var Closure|null */
    protected ?Closure $translator = null;

    /**
     * @return Closure|null
     */
    public function getTranslator(): ?Closure
    {
        return $this->translator;
    }

    /**
     * @param  Closure|null  $translator
     * @return void
     */
    public function setTranslator(?Closure $translator): void
    {
        $this->translator = $translator;
    }


    /**
     * @param  string  $key
     * @param  array  $attributes
     * @return string
     */
    protected function trans(string $key, array $attributes): string
    {
        $translator = $this->getTranslator();
        if ($translator && $translator instanceof Closure) {
            return $translator($key, $attributes);
        }

        return $key;
    }
}
