<?php

namespace Dg482\Mrd\Builder\Form\Buttons;

/**
 * Class BaseButton
 * @package Dg482\Mrd\Builder\Form\Buttons
 */
abstract class BaseButton
{

    /**
     * @var string
     */
    public string $text = 'button';

    /**
     * @var string
     */
    public string $icon = '';

    /**
     * can be set to circle, round or omitted
     * @var ?string
     */
    public ?string $shape = null;

    /**
     * can be set to primary, ghost, dashed, danger, link or omitted (meaning default)
     * @var string
     */
    public string $type = 'default';

    /**
     * option to fit button width to its parent width
     * @var bool
     */
    public bool $block = false;

    /** @var string */
    public string $action = '';

    /** @var bool  */
    public bool $load = false;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return BaseButton
     */
    public function setText(string $text): BaseButton
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return BaseButton
     */
    public function setIcon(string $icon): BaseButton
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getShape(): string
    {
        return $this->shape;
    }

    /**
     * @param string $shape
     * @return BaseButton
     */
    public function setShape(string $shape): BaseButton
    {
        $this->shape = $shape;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return BaseButton
     */
    public function setType(string $type): BaseButton
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBlock(): bool
    {
        return $this->block;
    }

    /**
     * @param bool $block
     * @return BaseButton
     */
    public function setBlock(bool $block): BaseButton
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return BaseButton
     */
    public function setAction(string $action): BaseButton
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLoad(): bool
    {
        return $this->load;
    }

    /**
     * @param bool $load
     * @return BaseButton
     */
    public function setLoad(bool $load): BaseButton
    {
        $this->load = $load;

        return $this;
    }
}
