<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Datapoints;

class Hourly
{
    /**
     * @var string
     */
    public $summary;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var HourDataPoint[]
     */
    public $data;
}
