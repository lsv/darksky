<?php

declare(strict_types=1);

namespace Lsv\Darksky;

use Lsv\Darksky\Serializer\TimestampNormalizer;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class Request
{
    public const ALLOWED_LANGUAGES = 'ar,az,be,bg,bn,bs,ca,cs,da,de,el,en,eo,es,et,fi,fr,he,hi,hr,hu,id,is,it,ja,ka,kn,ko,kw,lv,ml,mr,nb,nl,no,pa,pl,pt,ro,ru,sk,sl,sr,sv,ta,te,tet,tr,uk,ur,x-pig-latin,zh,zh-tw';

    protected const BASE_URL = 'https://api.darksky.net';

    /**
     * @var array
     */
    protected $queryParameters = [];

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    public function __construct(string $apiKey, HttpClientInterface $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    protected function makeRequest()
    {
        $resolver = new OptionsResolver();
        $this->resolve($resolver);
        $this->queryParameters = $resolver->resolve($this->queryParameters);

        $this->response = $this->client->request(
            $this->requestMethod(),
            $this->getUrl(),
            array_merge($this->requestOptions(), ['query' => $this->queryParameters])
        );

        if (200 !== $this->response->getStatusCode()) {
            throw new TransportException($this->response->getContent(), $this->response->getStatusCode());
        }

        return $this->parseResponse($this->response);
    }

    protected function requestMethod(): string
    {
        return 'GET';
    }

    protected function requestOptions(): array
    {
        return [];
    }

    protected function getSerializer(?string $timezone): Serializer
    {
        $extractor = new PropertyInfoExtractor(
            [],
            [new PhpDocExtractor()]
        );

        $normalizers = [
            new ArrayDenormalizer(),
            new TimestampNormalizer($timezone),
            new ObjectNormalizer(null, null, null, $extractor),
        ];

        $encoders = [
            new JsonEncoder(),
        ];

        return new Serializer(
            $normalizers,
            $encoders
        );
    }

    /**
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    abstract protected function parseResponse(ResponseInterface $response);

    abstract protected function resolve(OptionsResolver $resolver): void;

    abstract protected function getUrl(): string;
}
