<?php

declare(strict_types=1);

namespace Lsv\Darksky\Serializer;

use DateTime;
use DateTimeZone;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class TimestampNormalizer extends DateTimeNormalizer
{
    public function __construct(?string $timezone, $defaultContext = [])
    {
        $datetimezone = null;
        if ($timezone) {
            $datetimezone = new DateTimeZone($timezone);
        }

        parent::__construct($defaultContext, $datetimezone);
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (is_int($data)) {
            return (new DateTime())->setTimestamp($data);
        }

        return parent::denormalize($data, $class, $format, $context);
    }
}
