<?php

declare(strict_types=1);

namespace Lsv\Darksky;

use Lsv\Darksky\Response\ForecastResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class Forecast extends Request
{
    use RequestParameters;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    public function call(float $latitude, float $longitude): ForecastResponse
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        return $this->makeRequest();
    }

    protected function parseResponse(ResponseInterface $response): ForecastResponse
    {
        $data = $response->toArray();

        /** @var ForecastResponse $object */
        $object = $this->getSerializer($data['timezone'] ?? null)->deserialize(
            json_encode($data),
            ForecastResponse::class,
            'json'
        );

        return $object;
    }

    protected function resolve(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['exclude', 'extend', 'lang', 'units']);
        $resolver->addAllowedValues('exclude', $this->excludeAllowedBlocks());
        $resolver->addAllowedValues('extend', 'hourly');
        $resolver->addAllowedValues('lang', $this->languageAllowed());
        $resolver->addAllowedValues('units', $this->unitsAllowed());
    }

    protected function getUrl(): string
    {
        return sprintf(
            '%s/forecast/%s/%s,%s',
            self::BASE_URL,
            $this->getApiKey(),
            $this->latitude,
            $this->longitude
        );
    }
}
