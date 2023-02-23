<?php

namespace Pforret\SunnySideUp\Sources;

use Pforret\SunnySideUp\Exceptions\InvalidUrlError;
use Pforret\SunnySideUp\Formats\DayData;
use Pforret\SunnySideUp\Formats\ProductionData;
use Pforret\SunnySideUp\Formats\ProductionResponse;
use Pforret\SunnySideUp\Formats\StationData;

class FusionSolarSource implements SourceInterface
{
    use UrlGrabber;

    /**
     * @throws InvalidUrlError
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
        $response = new ProductionResponse();
        $response->stationData = new StationData();
        $response->stationData->url = $url;
        $response->stationData->id = $data['stationOverview']['stationDn'] ?? '';
        $response->stationData->address = $data['stationOverview']['plantAddress'] ?? '';
        $response->stationData->name = $data['stationOverview']['stationName'] ?? '';

        $response->dayData = new DayData();

        $response->dayProduction = new ProductionData();
        $response->dayProduction->kwhSystem = $data['realKpi']['dailyEnergy'] ?? null;

        $response->monthProduction = new ProductionData();
        $response->monthProduction->kwhSystem = $data['realKpi']['monthEnergy'] ?? null;

        $response->yearProduction = new ProductionData();
        $response->yearProduction->kwhSystem = $data['realKpi']['yearEnergy'] ?? null;

        $response->totalProduction = new ProductionData();
        $response->totalProduction->kwhSystem = $data['realKpi']['cumulativeEnergy'] ?? null;

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
        preg_match("|kk=(\w+)$|", $url, $params);

        return $params[1] ?? '';
    }
}
