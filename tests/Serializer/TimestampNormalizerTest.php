<?php

declare(strict_types=1);

namespace Lsv\DarkskyTests\Serializer;

use DateTimeInterface;
use Lsv\Darksky\Serializer\TimestampNormalizer;
use PHPUnit\Framework\TestCase;
use stdClass;

class TimestampNormalizerTest extends TestCase
{
    /**
     * @test
     */
    public function can_normalize_timestamp(): void
    {
        $timezone = 'Europe/Copenhagen';
        $normalizer = new TimestampNormalizer($timezone);
        $denormalized = $normalizer->denormalize(1509993277, new stdClass());
        $this->assertInstanceOf(DateTimeInterface::class, $denormalized);
        $this->assertSame('19:34', $denormalized->format('H:i'));
    }

    /**
     * @test
     */
    public function can_normalize_string(): void
    {
        $timezone = 'Europe/Copenhagen';
        $normalizer = new TimestampNormalizer($timezone);
        $denormalized = $normalizer->denormalize('2019-05-03 19:34:00', new stdClass());
        $this->assertInstanceOf(DateTimeInterface::class, $denormalized);
        $this->assertSame('19:34', $denormalized->format('H:i'));
    }
}
