<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Datapoints;

use Lsv\Darksky\Response\Traits\PrecipTrait;
use Lsv\Darksky\Response\Traits\TimeTrait;

class MinuteDataPoint
{
    use PrecipTrait;
    use TimeTrait;
}
