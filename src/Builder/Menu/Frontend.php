<?php

namespace Dg482\Mrd\Builder\Menu;

/**
 * Class Frontend
 * @package Dg482\Mrd\Builder\Menu
 */
class Frontend
{
    /** @var array */
    protected array $menu = [];

    /** @var array */
    protected array $routes = [];

    /**
     * @return array[MenuItem]
     */
    public function getMenu(): array
    {
        return $this->menu;
    }

    /**
     * @param  MenuItem  $menu
     * @return Frontend
     */
    public function setMenu(MenuItem $menu): Frontend
    {
        $this->menu[] = $menu;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param  array  $routes
     * @return Frontend
     */
    public function setRoutes(array $routes): Frontend
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * @return array
     */
    public function getMenuItems(): array
    {
        $result = [];
        array_map(function (MenuItem $item) use (&$result) {

            array_push($result, $item->getData());
        }, $this->getMenu());

        return $result;
    }
}
