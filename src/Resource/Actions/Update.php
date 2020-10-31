<?php

namespace Dg482\Mrd\Resource\Actions;

/**
 * Class Update
 * @package Dg482\Mrd\Resource\Actions
 */
class Update extends Crud
{
    /** @var string */
    protected string $action = 'update';

    /** @var string */
    protected string $icon = 'edit';

    /** @var string */
    protected string $text = 'Редактировать';
}
