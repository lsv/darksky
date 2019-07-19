<?php

declare(strict_types=1);

namespace Lsv\DarkskyTests;

use Lsv\Darksky\Forecast;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function throw_exception_if_status_code_not_met(): void
    {
        $this->expectException(TransportException::class);
        $this->expectExceptionMessageRegExp('/Limit/');

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->method('getContent')
            ->willReturn('Limit overdue');
        $response
            ->method('getStatusCode')
            ->willReturn(201);

        $client = new MockHttpClient($response);

        $forecast = new Forecast('apikey', $client);
        $forecast->call(10, 10);
    }
}
