<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Traits;

trait WindTrait
{
    /**
     * @var float
     */
    public $windSpeed;

    /**
     * @var float
     */
    public $windGust;

    /**
     * @var int
     */
    public $windBearing;

    /**
     * @var float
     */
    public $cloudCover;
}
