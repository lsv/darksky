<?php

declare(strict_types=1);

namespace Lsv\Darksky;

use DateTime;
use Lsv\Darksky\Response\TimemachineResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Timemachine extends Request
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

    /**
     * @var DateTime
     */
    private $time;

    public function call(
        float $latitude,
        float $longitude,
        DateTime $time
    ): TimemachineResponse {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->time = $time;

        return $this->makeRequest();
    }

    protected function parseResponse(ResponseInterface $response): TimemachineResponse
    {
        $data = $response->toArray();

        /** @var TimemachineResponse $object */
        $object = $this->getSerializer($data['timezone'] ?? null)->deserialize(
            json_encode($data),
            TimemachineResponse::class,
            'json'
        );

        return $object;
    }

    protected function resolve(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['exclude', 'lang', 'units']);
        $resolver->addAllowedValues('exclude', $this->excludeAllowedBlocks());
        $resolver->addAllowedValues('lang', $this->languageAllowed());
        $resolver->addAllowedValues('units', $this->unitsAllowed());
    }

    protected function getUrl(): string
    {
        return sprintf(
            '%s/forecast/%s/%s,%s,%s',
            self::BASE_URL,
            $this->getApiKey(),
            $this->latitude,
            $this->longitude,
            $this->time->format('c')
        );
    }
}
