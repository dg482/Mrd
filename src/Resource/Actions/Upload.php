<?php

namespace Dg482\Mrd\Resource\Actions;

/**
 * Class Upload
 * @package Dg482\Mrd\Resource\Actions
 */
class Upload extends Create
{
    /** @var string */
    protected string $action = 'upload';

    /** @var string */
    protected string $icon = 'upload';

    /** @var string */
    protected string $text = 'Загрузить';
}
