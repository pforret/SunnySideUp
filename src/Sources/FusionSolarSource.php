<?php

namespace Pforret\SunnySideUp\Sources;

use Pforret\SunnySideUp\Exceptions\InvalidContentError;
use Pforret\SunnySideUp\Exceptions\InvalidUrlError;
use Pforret\SunnySideUp\Formats\CurrentData;
use Pforret\SunnySideUp\Formats\DayWeather;
use Pforret\SunnySideUp\Formats\ProductionData;
use Pforret\SunnySideUp\Formats\ProductionResponse;
use Pforret\SunnySideUp\Formats\StationData;
use Pforret\SunnySideUp\Helpers\UrlGrabber;

class FusionSolarSource implements SourceInterface
{
    use UrlGrabber;

    /**
     * @throws InvalidUrlError
     * @throws InvalidContentError
     */
    public function get(string $url): ProductionResponse
    {
        $hostname = parse_url($url, PHP_URL_HOST);
        $key = self::getKey($url);
        $url = sprintf('https://%s/rest/pvms/web/kiosk/v1/station-kiosk-file?kk=%s', $hostname, $key);
        $referer = sprintf('https://%s/pvmswebsite/nologin/assets/build/index.html', $hostname);
        $html = $this->getUrl($url, $referer);
        $json = json_decode($html, true);
        $data = json_decode(htmlspecialchars_decode($json['data'] ?? ''), true);
        if (! isset($data['stationOverview'])) {
            throw new InvalidContentError();
        }
        // print_r($data);
        /*
        Array
        (
            [powerCurve] => Array
                (
                    [xAxis] => Array
                        (
                            [0] => 00:00
                            [1] => 00:05
                            // ...
                            [286] => 23:50
                            [287] => 23:55
                        )

                    [currentPower] => 0.48
                    [activePower] => Array
                        (
                            [0] => 0.00
                            [1] => 0.00
                            // ...
                            [125] => 2.86
                            [126] => 3.32
                            // ...
                            [286] => -
                            [287] => -
                        )

                )

            [stationOverview] => Array
                (
                    [stationName] => (station name)
                    [plantAddress] => (street address)
                    [stationDn] => (station id))
                )

            [language] => en-US
            [socialContribution] => Array
                (
                    [co2ReductionByYear] => 55.8
                    [equivalentTreePlantingByYear] => 1
                    [standardCoalSavings] => 69.14
                    [cumulativeReductionDust] => 46.95
                    [equivalentTreePlanting] => 1
                    [co2Reduction] => 82.1
                    [standardCoalSavingsByYear] => 46.99
                )

            [realKpi] => Array
                (
                    [realTimePower] => 2.86
                    [cumulativeEnergy] => 172.85
                    [monthEnergy] => 86.21
                    [dailyEnergy] => 0.87
                    [yearEnergy] => 117.47
                )

        )
         */
        $response = new ProductionResponse();
        $response->stationData = new StationData();
        $response->stationData->url = $url;
        $response->stationData->id = $data['stationOverview']['stationDn'] ?? '';
        $response->stationData->address = $data['stationOverview']['plantAddress'] ?? '';
        $response->stationData->name = $data['stationOverview']['stationName'] ?? '';

        $response->dayWeather = new DayWeather();

        $response->currentData = new CurrentData();
        $response->currentData->currentPowerKw = $data['powerCurve']['currentPower'] ?? '';

        $response->dayProduction = new ProductionData();
        $response->dayProduction->kwhSystem = $data['realKpi']['dailyEnergy'] ?? null;

        $response->monthProduction = new ProductionData();
        $response->monthProduction->kwhSystem = $data['realKpi']['monthEnergy'] ?? null;

        $response->yearProduction = new ProductionData();
        $response->yearProduction->kwhSystem = $data['realKpi']['yearEnergy'] ?? null;
        $response->yearProduction->equivalentKgCo2 = $data['socialContribution']['co2ReductionByYear'] ?? null;
        $response->yearProduction->equivalentTrees = $data['socialContribution']['equivalentTreePlantingByYear'] ?? null;
        $response->yearProduction->equivalentKgCoal = $data['socialContribution']['standardCoalSavingsByYear'] ?? null;

        $response->totalProduction = new ProductionData();
        $response->totalProduction->kwhSystem = $data['realKpi']['cumulativeEnergy'] ?? null;
        $response->totalProduction->equivalentKgCo2 = $data['socialContribution']['co2Reduction'] ?? null;
        $response->totalProduction->equivalentTrees = $data['socialContribution']['equivalentTreePlanting'] ?? null;
        $response->totalProduction->equivalentKgCoal = $data['socialContribution']['standardCoalSavings'] ?? null;

        return $response;
    }

    /**
     * @throws InvalidUrlError
     */
    public static function getKey(string $url)
    {
        $params = [];
        if (! str_contains($url, 'kk=')) {
            throw new InvalidUrlError();
        }
        preg_match('|kk=([^&]+)|', $url, $params);

        return $params[1] ?? '';
    }
}
