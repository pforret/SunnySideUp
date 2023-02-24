<?php

namespace Pforret\SunnySideUp\Sources;

use Pforret\SunnySideUp\Formats\DayWeather;
use Pforret\SunnySideUp\Formats\ProductionData;
use Pforret\SunnySideUp\Formats\ProductionResponse;
use Pforret\SunnySideUp\Formats\StationData;

class FakeSource implements SourceInterface
{
    public function get(string $url): ProductionResponse
    {
        $response = new ProductionResponse();
        $response->dayWeather = new DayWeather();
        $response->dayProduction = new ProductionData();
        $response->monthProduction = new ProductionData();
        $response->yearProduction = new ProductionData();
        $response->totalProduction = new ProductionData();
        $response->stationData = new StationData();

        return $response;
    }
}
