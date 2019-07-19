Darksky.net API wrapper
-----------------------

[![Build Status](https://travis-ci.org/lsv/darksky.svg?branch=master)](https://travis-ci.org/lsv/darksky) [![codecov](https://codecov.io/gh/lsv/darksky/branch/master/graph/badge.svg)](https://codecov.io/gh/lsv/darksky)

PHP Wrapper for [darksky.net](https://darksky.net) weather api.

Supporting

- [x] Forecast
- [ ] TimeMachine

### Install

```
composer require lsv/darksky-php-wrapper
```

### Usage

```
$client = new Symfony\Component\HttpClient\HttpClient();
$apikey = 'your-api-key';
$latitude = 42.3601;
$longitude = -71.0589;

$forecast = new \Lsv\Darksky\Forecast($apikey, $client);
// Set the optional parameters
// See https://darksky.net/dev/docs#forecast-request for possible parameters
$forecast->exclude(['currently']); // Array of blocks to exclude from the call
$forecast->extendHourly(); // Extend hourly forecast to 148 hours
$forecast->language('da'); // Set the language
$forecast->units('si'); // Change the units

$response = $forecast->call($latitude, $longitude);
// $response is now a \Lsv\Darksky\Response\ForecastResponse object
``` 
