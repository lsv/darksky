<?php

declare(strict_types=1);

namespace Lsv\Darksky\Response\Traits;

trait PrecipTrait
{
    /**
     * @var float
     */
    public $precipIntensity;

    /**
     * @var float
     */
    public $precipIntensityError;

    /**
     * @var float
     */
    public $precipProbability;

    /**
     * @var string
     */
    public $precipType;
}
