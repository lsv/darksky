<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Datapoints;

class Minutely
{
    public $summary;

    public $icon;

    /**
     * @var MinuteDataPoint[]
     */
    public $data;
}
