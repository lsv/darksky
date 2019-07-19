<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Datapoints;

use Lsv\Darksky\Response\Traits\HumidTrait;
use Lsv\Darksky\Response\Traits\PrecipTrait;
use Lsv\Darksky\Response\Traits\TemperatureTrait;
use Lsv\Darksky\Response\Traits\TimeTrait;
use Lsv\Darksky\Response\Traits\WindTrait;

class Currently
{
    use PrecipTrait;
    use TimeTrait;
    use TemperatureTrait;
    use HumidTrait;
    use WindTrait;

    /**
     * @var string
     */
    public $summary;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var float
     */
    public $nearestStormDistance;

    /**
     * @var int
     */
    public $uvIndex;

    /**
     * @var float
     */
    public $visibility;

    /**
     * @var float
     */
    public $ozone;
}
