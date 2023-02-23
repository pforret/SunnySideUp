<?php

namespace Pforret\SunnySideUp\Formats;

class ProductionResponse
{

    public StationData $stationData;
    public DayData $dayData;
    public ProductionData $dayProduction;
    public ProductionData $monthProduction;
    public ProductionData $yearProduction;
    public ProductionData $totalProduction;
}
