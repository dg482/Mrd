<?php

namespace Dg482\Mrd\Commands\Crud;

use Dg482\Mrd\Commands\Interfaces\CommandInterfaces;

/**
 * Class Read
 * @package Dg482\Mrd\Commands\Crud
 */
class Read extends Command implements CommandInterfaces
{
    /** @var int $perPage */
    protected int $perPage = 25;

    /**
     * @return bool
     */
    public function execute(): bool
    {
        return $this->getAdapter()->readCmd();
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     * @return $this
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

}
