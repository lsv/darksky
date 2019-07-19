<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Traits;

trait HumidTrait
{
    /**
     * @var float
     */
    public $dewPoint;

    /**
     * @var float
     */
    public $humidity;

    /**
     * @var float
     */
    public $pressure;
}
