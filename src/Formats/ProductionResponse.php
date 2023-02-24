<?php

namespace Pforret\SunnySideUp\Formats;

class ProductionResponse
{
    public StationData $stationData;

    public DayWeather $dayWeather;

    public CurrentData $currentData;

    public ProductionData $dayProduction;

    public ProductionData $monthProduction;

    public ProductionData $yearProduction;

    public ProductionData $totalProduction;
}
