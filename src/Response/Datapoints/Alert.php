<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Datapoints;

use DateTime;

class Alert
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var DateTime
     */
    public $time;

    /**
     * @var DateTime
     */
    public $expires;

    /**
     * @var string
     */
    public $description;
}
