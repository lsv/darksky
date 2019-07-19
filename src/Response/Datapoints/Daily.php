<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Datapoints;

class Daily
{
    public $summary;

    public $icon;

    /**
     * @var DayDataPoint[]
     */
    public $data;
}
