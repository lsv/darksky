<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response;

use Lsv\Darksky\Response\Datapoints\Alert;
use Lsv\Darksky\Response\Datapoints\Currently;
use Lsv\Darksky\Response\Datapoints\Daily;
use Lsv\Darksky\Response\Datapoints\Hourly;
use Lsv\Darksky\Response\Datapoints\Minutely;

class ForecastResponse
{
    /**
     * @var float
     */
    public $latitude;

    /**
     * @var float
     */
    public $longitude;

    /**
     * @var string
     */
    public $timezone;

    /**
     * @var Currently
     */
    public $currently;

    /**
     * @var Minutely
     */
    public $minutely;

    /**
     * @var Hourly
     */
    public $hourly;

    /**
     * @var Daily
     */
    public $daily;

    /**
     * @var Alert[]
     */
    public $alerts;

    /**
     * @var array
     */
    public $flags;
}
