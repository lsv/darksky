<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Datapoints;

use DateTime;
use Lsv\Darksky\Response\Traits\HumidTrait;
use Lsv\Darksky\Response\Traits\PrecipTrait;
use Lsv\Darksky\Response\Traits\TimeTrait;
use Lsv\Darksky\Response\Traits\WindTrait;

class DayDataPoint
{
    use TimeTrait;
    use PrecipTrait;
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
     * @var DateTime
     */
    public $sunriseTime;

    /**
     * @var DateTime
     */
    public $sunsetTime;

    /**
     * @var float
     */
    public $moonPhase;

    /**
     * @var float
     */
    public $precipIntensityMax;

    /**
     * @var DateTime
     */
    public $precipIntensityMaxTime;

    /**
     * @var float
     */
    public $temperatureHigh;

    /**
     * @var DateTime
     */
    public $temperatureHighTime;

    /**
     * @var float
     */
    public $temperatureLow;

    /**
     * @var DateTime
     */
    public $temperatureLowTime;

    /**
     * @var float
     */
    public $apparentTemperatureHigh;

    /**
     * @var DateTime
     */
    public $apparentTemperatureHighTime;

    /**
     * @var float
     */
    public $apparentTemperatureLow;

    /**
     * @var DateTime
     */
    public $apparentTemperatureLowTime;

    /**
     * @var DateTime
     */
    public $windGustTime;

    /**
     * @var int
     */
    public $uvIndex;

    /**
     * @var DateTime
     */
    public $uvIndexTime;

    /**
     * @var float
     */
    public $visibility;

    /**
     * @var float
     */
    public $ozone;

    /**
     * @var float
     */
    public $temperatureMin;

    /**
     * @var DateTime
     */
    public $temperatureMinTime;

    /**
     * @var float
     */
    public $temperatureMax;

    /**
     * @var DateTime
     */
    public $temperatureMaxTime;

    /**
     * @var float
     */
    public $apparentTemperatureMin;

    /**
     * @var DateTime
     */
    public $apparentTemperatureMinTime;

    /**
     * @var float
     */
    public $apparentTemperatureMax;

    /**
     * @var DateTime
     */
    public $apparentTemperatureMaxTime;
}
