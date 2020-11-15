<?php

namespace Dg482\Mrd\Builder\Form\Fields;

use Dg482\Mrd\Model;

/**
 * Class Badge
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class Badge
{
    /**
     * Типы
     */
    const TYPE_SUCCESS = 'badge badge-success';
    const TYPE_WARNING = 'badge badge-warning';
    const TYPE_DANGER = 'badge badge-danger';
    const TYPE_PRIMARY = 'badge badge-primary';
    const TYPE_DEFAULT = 'badge badge-secondary';
    const TYPE_INFO = 'badge badge-info';
    const TYPE_FOCUS = 'badge badge-focus';
    const TYPE_ALT = 'badge badge-alternate';
    const TYPE_LIGHT = 'badge badge-light';
    const TYPE_DARK = 'badge badge-dark';


    const BADGE_TYPE_DEFAULT = 'default';
    const BADGE_TYPE_COUNTER = 'antd-count';
    const BADGE_TYPE_TEXT = 'antd-text';

    /** @var string $text */
    protected string $text = '';

    /** @var string $type */
    protected string $type = self::TYPE_DEFAULT;

    /** @var string $description */
    protected string $description = '';

    /** @var string $badgeType */
    protected string $badgeType = self::BADGE_TYPE_DEFAULT;

    /**
     * @param  string  $text
     * @param  string  $type
     * @return Badge
     */
    public function make(string $text = '', string $type = self::TYPE_DEFAULT)
    {
        $this->setText($text);
        $this->setType($type);

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param  string  $text
     * @return $this
     */
    public function setText(string $text): Badge
    {
        $this->text = $text;
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
     * @param  string  $type
     * @return Badge
     */
    public function setType(string $type): Badge
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param  string  $description
     * @return Badge
     */
    public function setDescription(string $description): Badge
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getBadgeType(): string
    {
        return $this->badgeType;
    }

    /**
     * @param  string  $badgeType
     * @return Badge
     */
    public function setBadgeType(string $badgeType): Badge
    {
        $this->badgeType = $badgeType;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'badge' => $this->getBadgeType(),
            'text' => $this->getText(),
            'type' => $this->getType(),
            'description' => $this->getDescription(),
        ];
    }
}
