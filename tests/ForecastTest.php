<?php

declare(strict_types=1);

namespace Lsv\DarkskyTests;

use DateTime;
use DateTimeZone;
use Lsv\Darksky\Forecast;
use Lsv\Darksky\Response\Datapoints\Alert;
use Lsv\Darksky\Response\Datapoints\Currently;
use Lsv\Darksky\Response\Datapoints\DayDataPoint;
use Lsv\Darksky\Response\Datapoints\HourDataPoint;
use Lsv\Darksky\Response\Datapoints\MinuteDataPoint;
use Lsv\Darksky\Response\Datapoints\Minutely;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class ForecastTest extends TestCase
{
    /**
     * @var Forecast
     */
    private $forecast;

    /**
     * @test
     */
    public function can_not_make_request_when_excluding_not_existing_block(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessageRegExp('/exclude/');

        $this->forecast->excludeBlocks(['not_existing']);
        $this->forecast->call(42.3601, -71.0589);
    }

    /**
     * @test
     */
    public function can_not_make_request_with_not_existing_language(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessageRegExp('/lang/');

        $this->forecast->language('not_existing');
        $this->forecast->call(42.3601, -71.0589);
    }

    /**
     * @test
     */
    public function can_not_make_request_with_not_existing_unit(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessageRegExp('/units/');

        $this->forecast->units('not_existing');
        $this->forecast->call(42.3601, -71.0589);
    }

    /**
     * @test
     */
    public function can_make_request_with_parameters(): void
    {
        $this->forecast->excludeBlocks(['alerts']);
        $this->forecast->extendHourly();
        $this->forecast->language('da');
        $this->forecast->units('uk2');
        $this->forecast->call(42.3601, -71.0589);
        $response = $this->forecast->getResponse();
        $this->assertStringEqualsFile(__DIR__.'/stubs/forecast.json', $response->getContent());
    }

    /**
     * @test
     */
    public function can_serialize_forecast_response(): void
    {
        $response = $this->forecast->call(42.3601, -71.0589);

        $this->assertSame(42.3601, $response->latitude);
        $this->assertSame(-71.0589, $response->longitude);
        $this->assertSame('America/New_York', $response->timezone);

        // Currently
        $this->assertInstanceOf(Currently::class, $response->currently);
        $this->assertSame('Drizzle', $response->currently->summary);
        $this->assertSame('rain', $response->currently->icon);
        $this->assertSame(0.0, $response->currently->nearestStormDistance);
        $this->assertSame(1, $response->currently->uvIndex);
        $this->assertSame(9.84, $response->currently->visibility);
        $this->assertSame(267.44, $response->currently->ozone);
        $this->assertSame(0.0089, $response->currently->precipIntensity);
        $this->assertSame(0.0046, $response->currently->precipIntensityError);
        $this->assertSame(0.9, $response->currently->precipProbability);
        $this->assertSame('rain', $response->currently->precipType);
        $this->assertSame(66.1, $response->currently->temperature);
        $this->assertSame(66.31, $response->currently->apparentTemperature);
        $this->assertSame(60.77, $response->currently->dewPoint);
        $this->assertSame(0.83, $response->currently->humidity);
        $this->assertSame(1010.34, $response->currently->pressure);
        $this->assertSame(5.59, $response->currently->windSpeed);
        $this->assertSame(12.03, $response->currently->windGust);
        $this->assertSame(246, $response->currently->windBearing);
        $this->assertSame(0.7, $response->currently->cloudCover);

        $this->assertInstanceOf(DateTime::class, $response->currently->time);
        $time = clone $response->currently->time;
        $time->setTimezone(new DateTimeZone('Europe/Copenhagen'));
        $this->assertSame('19:34', $time->format('H:i'));
        $ustime = clone $time;
        $ustime->setTimezone(new DateTimeZone('America/New_York'));
        $this->assertSame('13:34', $ustime->format('H:i'));

        // Minutely
        $this->assertInstanceOf(Minutely::class, $response->minutely);
        $this->assertSame(
            'Light rain stopping in 13 min., starting again 30 min. later.',
            $response->minutely->summary
        );
        $this->assertSame('rain', $response->minutely->icon);
        $this->assertCount(1, $response->minutely->data);
        $datapoint = $response->minutely->data[0];
        $this->assertInstanceOf(MinuteDataPoint::class, $datapoint);
        $this->assertInstanceOf(DateTime::class, $datapoint->time);
        $this->assertSame(0.007, $datapoint->precipIntensity);
        $this->assertSame(0.004, $datapoint->precipIntensityError);
        $this->assertSame(0.84, $datapoint->precipProbability);
        $this->assertSame('rain', $datapoint->precipType);

        // Hourly
        $this->assertCount(1, $response->hourly->data);
        $datapoint = $response->hourly->data[0];
        $this->assertInstanceOf(HourDataPoint::class, $datapoint);
        $this->assertInstanceOf(DateTime::class, $datapoint->time);
        $this->assertSame('Mostly Cloudy', $datapoint->summary);
        $this->assertSame('partly-cloudy-day', $datapoint->icon);
        $this->assertSame(0.0007, $datapoint->precipIntensity);
        $this->assertSame(0.1, $datapoint->precipProbability);
        $this->assertSame('rain', $datapoint->precipType);
        $this->assertSame(65.76, $datapoint->temperature);
        $this->assertSame(66.01, $datapoint->apparentTemperature);
        $this->assertSame(60.99, $datapoint->dewPoint);
        $this->assertSame(0.85, $datapoint->humidity);
        $this->assertSame(1010.57, $datapoint->pressure);
        $this->assertSame(4.23, $datapoint->windSpeed);
        $this->assertSame(9.52, $datapoint->windGust);
        $this->assertSame(230, $datapoint->windBearing);
        $this->assertSame(0.62, $datapoint->cloudCover);
        $this->assertSame(1, $datapoint->uvIndex);
        $this->assertSame(9.32, $datapoint->visibility);
        $this->assertSame(268.95, $datapoint->ozone);

        // Daily
        $this->assertCount(1, $response->daily->data);
        $datapoint = $response->daily->data[0];
        $this->assertInstanceOf(DayDataPoint::class, $datapoint);
        $this->assertInstanceOf(DateTime::class, $datapoint->time);
        $this->assertSame('Rain starting in the afternoon, continuing until evening.', $datapoint->summary);
        $this->assertSame('rain', $datapoint->icon);
        $this->assertInstanceOf(DateTime::class, $datapoint->sunriseTime);
        $this->assertInstanceOf(DateTime::class, $datapoint->sunsetTime);
        $this->assertSame(0.59, $datapoint->moonPhase);
        // Precip
        $this->assertSame(0.0088, $datapoint->precipIntensity);
        $this->assertSame(0.0725, $datapoint->precipIntensityMax);
        $this->assertInstanceOf(DateTime::class, $datapoint->precipIntensityMaxTime);
        $this->assertSame(0.73, $datapoint->precipProbability);
        $this->assertSame('rain', $datapoint->precipType);
        // Temps
        $this->assertSame(66.35, $datapoint->temperatureHigh);
        $this->assertInstanceOf(DateTime::class, $datapoint->temperatureHighTime);
        $this->assertSame(41.28, $datapoint->temperatureLow);
        $this->assertInstanceOf(DateTime::class, $datapoint->temperatureLowTime);
        $this->assertSame(52.08, $datapoint->temperatureMin);
        $this->assertInstanceOf(DateTime::class, $datapoint->temperatureMinTime);
        $this->assertSame(66.35, $datapoint->temperatureMax);
        $this->assertInstanceOf(DateTime::class, $datapoint->temperatureMaxTime);
        $this->assertSame(52.08, $datapoint->apparentTemperatureMin);
        $this->assertInstanceOf(DateTime::class, $datapoint->apparentTemperatureMinTime);
        $this->assertSame(66.53, $datapoint->apparentTemperatureMax);
        $this->assertInstanceOf(DateTime::class, $datapoint->apparentTemperatureMaxTime);
        $this->assertSame(66.53, $datapoint->apparentTemperatureHigh);
        $this->assertInstanceOf(DateTime::class, $datapoint->apparentTemperatureHighTime);
        $this->assertSame(35.74, $datapoint->apparentTemperatureLow);
        $this->assertInstanceOf(DateTime::class, $datapoint->apparentTemperatureLowTime);

        $this->assertSame(57.66, $datapoint->dewPoint);
        $this->assertSame(0.86, $datapoint->humidity);
        $this->assertSame(1012.93, $datapoint->pressure);
        $this->assertSame(3.22, $datapoint->windSpeed);
        $this->assertSame(26.32, $datapoint->windGust);
        $this->assertInstanceOf(DateTime::class, $datapoint->windGustTime);
        $this->assertSame(270, $datapoint->windBearing);
        $this->assertSame(0.8, $datapoint->cloudCover);
        $this->assertSame(2, $datapoint->uvIndex);
        $this->assertInstanceOf(DateTime::class, $datapoint->uvIndexTime);
        $this->assertSame(10.0, $datapoint->visibility);
        $this->assertSame(269.45, $datapoint->ozone);

        // Alerts
        $this->assertCount(1, $response->alerts);
        $datapoint = $response->alerts[0];
        $this->assertInstanceOf(Alert::class, $datapoint);
        $this->assertInstanceOf(DateTime::class, $datapoint->time);
        $this->assertInstanceOf(DateTime::class, $datapoint->expires);
        $this->assertSame('Flood Watch for Mason, WA', $datapoint->title);
        $this->assertStringStartsWith('...FLOOD WATCH REMAINS', $datapoint->description);

        // Flags
        $this->assertIsArray($response->flags);
        $this->assertCount(11, $response->flags['sources']);
        $this->assertSame(1.835, $response->flags['nearest-station']);
        $this->assertSame('us', $response->flags['units']);
    }

    protected function setUp(): void
    {
        $response = new MockResponse(file_get_contents(__DIR__.'/stubs/forecast.json'));
        $client = new MockHttpClient($response);
        $this->forecast = new Forecast('testing', $client);
    }
}
