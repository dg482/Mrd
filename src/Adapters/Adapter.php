<?php

namespace Dg482\Mrd\Adapters;

use Dg482\Mrd\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Mrd\Commands\Crud\Command;
use Dg482\Mrd\Commands\Crud\Read;
use Dg482\Mrd\Commands\Interfaces\CommandInterfaces;
use IteratorAggregate;

/**
 * Class Adapter
 * @package Dg482\Mrd\adapters
 */
abstract class Adapter implements AdapterInterfaces
{
    /** @var CommandInterfaces|Command */
    private $command;

    /**
     * @param int $limit
     * @return array|bool|IteratorAggregate
     */
    public function read($limit = 1)
    {
        $cmd = (new Read())
            ->setAdapter($this)
            ->setMultiple($limit > 1)
            ->setPerPage($limit);

        $this->setCommand($cmd);

        $this->execute();

        return $this->getCommand()->getResult();
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        return $this->command->execute();
    }

    /**
     * @return Command|CommandInterfaces
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param CommandInterfaces $cmd
     */
    public function setCommand(CommandInterfaces $cmd): void
    {
        $this->command = $cmd;
    }

    /**
     * @return bool
     */
    public function write(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function update(): bool
    {
        return false;
    }
}
