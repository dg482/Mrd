<?php

namespace Dg482\Mrd\Builder\Form;

use Dg482\Mrd\Builder\Form\Fields\Badge;

/**
 * Trait BadgeTrait
 * @package Dg482\Mrd\Builder\Form
 */
trait BadgeTrait
{
    /**
     * Бейдж, выводится внизу поля
     * @var Badge|null $badge
     */
    protected ?Badge $badge = null;

    /**
     * Коллекция бейджей
     * @var array $badges
     */
    protected array $badges = [];

    /**
     * @return ?Badge
     */
    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    /**
     * @param $badge
     * @return void
     */
    public function setBadge(Badge $badge): void
    {
        $this->badge = $badge;
    }

    /**
     * @param  Badge  $badge
     * @return void
     */
    public function pushBadge(Badge $badge): void
    {
        array_push($this->badges, $badge);
    }

    /**
     * @return array
     */
    public function getBadges(): array
    {
        return $this->badges;
    }
}
