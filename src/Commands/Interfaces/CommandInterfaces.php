<?php

namespace Dg482\Mrd\Commands\Interfaces;

/**
 * Interface CommandInterfaces
 * @package Dg482\Mrd\Commands\Interfaces
 */
interface CommandInterfaces
{
    /**
     * @return bool
     */
    public function execute(): bool;

    /**
     * @return array
     */
    public function getResult(): array;

    /**
     * @param $result
     * @return CommandInterfaces
     */
    public function setResult($result): CommandInterfaces;
}
