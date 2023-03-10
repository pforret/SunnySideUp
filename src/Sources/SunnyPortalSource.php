<?php

namespace Pforret\SunnySideUp\Sources;

use Pforret\SunnySideUp\Formats\CurrentData;
use Pforret\SunnySideUp\Formats\DayWeather;
use Pforret\SunnySideUp\Formats\ProductionData;
use Pforret\SunnySideUp\Formats\ProductionResponse;
use Pforret\SunnySideUp\Formats\StationData;
use Pforret\SunnySideUp\Helpers\StringCleaner;
use Pforret\SunnySideUp\Helpers\UrlGrabber;

class SunnyPortalSource implements SourceInterface
{
    use UrlGrabber;

    public function get(string $url): ProductionResponse
    {
        $html = $this->getUrl($url);
        $response = new ProductionResponse();

        /*
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue" class="mainValueAmount simpleTextFit">7483</span>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldUnit" class="mainValueUnit">Wh</span><br/>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldPeriodTitle" class="mainValueDescription">Today</span>

        <div id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldTotalDiv" class="widgetFooter">
        Total:&nbsp;<span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldTotalValue">16.385</span>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldTotalUnit">MWh</span>

         */

        $response->stationData = new StationData();
        $response->stationData->url = $url;
        $response->stationData->timezone = self::parseRegexFromHtml($html, "|standardName: '([^']+)'|");
        $kilo_watt_peak = self::parseRegexFromHtml($html, "|<div><strong>([\d\.]+) kWp</strong></div>|");
        //             <div><strong>10.30 kWp</strong></div>
        $watt_peak = self::parseRegexFromHtml($html, "|<div><strong>(\d+) Wp</strong></div>|");

        if ($watt_peak) {
            $response->stationData->watt_peak = $watt_peak;
        } elseif ($kilo_watt_peak) {
            $response->stationData->watt_peak = (int) ($kilo_watt_peak * 1000);
        }
        $response->stationData->date_commissioning = self::parseRegexFromHtml($html, "|<div><strong>(\d+/\d/\d+)</strong></div>|");
        $response->stationData->name = StringCleaner::name(self::parseTextViaId($html, 'ctl00_ContentPlaceHolder1_title'));

        $response->currentData = new CurrentData();
        $response->currentData->currentPowerKw = (float) self::parseRegexFromHtml($html, '|data-value="(\d+)"|');
        $response->currentData->timeSampled = self::parseRegexFromHtml($html, '|data-timestamp="([^"]+)"|');

        $response->dayWeather = new DayWeather();

        $response->dayProduction = new ProductionData();
        $response->monthProduction = new ProductionData();
        $response->yearProduction = new ProductionData();
        $response->totalProduction = new ProductionData();

        $dailyCO2 = self::parseFloatViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_carbonWidget_carbonReductionValue');
        $co2_unit = self::parseTextViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_carbonWidget_carbonReductionUnit');
        if ($dailyCO2 > 0) {
            if ($co2_unit == 'g') {
                // convert to kg
                $dailyCO2 = $dailyCO2 / 1000;
            }
        }

        $productionValue = self::parseFloatViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue');
        $productionUnit = self::parseTextViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldUnit');
        if ($productionUnit == 'Wh') {
            $productionValue = $productionValue / 1000;
        }

        $title = self::parseTextViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldPeriodTitle');
        switch (StringCleaner::text($title)) {
            case 'Today':
                $response->dayProduction->kwhSystem = $productionValue;
                $response->dayProduction->equivalentKgCo2 = $dailyCO2;
                break;

            case date('Y'):
                $response->yearProduction->kwhSystem = $productionValue;
                $response->yearProduction->equivalentKgCo2 = $dailyCO2;
                break;

            default:
                $response->monthProduction->kwhSystem = $productionValue;
                $response->monthProduction->equivalentKgCo2 = $dailyCO2;
        }

        $response->totalProduction->kwhSystem = self::parseFloatViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldTotalValue');
        $unit = self::parseTextViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldTotalUnit');
        if ($unit == 'MWh' && $response->totalProduction->kwhSystem > 0) {
            $response->totalProduction->kwhSystem = $response->totalProduction->kwhSystem * 1000;
        }

        $response->totalProduction->equivalentKgCo2 = self::parseFloatViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_carbonWidget_carbonReductionTotalValue');
        $unit = self::parseTextViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_carbonWidget_carbonReductionTotalUnit');
        if ($unit == 't') {
            $response->totalProduction->equivalentKgCo2 = $response->totalProduction->equivalentKgCo2 * 1000;
        }

        return $response;
    }

    public static function parseIntViaId(string $html, string $id, string $tag = 'span'): ?int
    {
        if ($response = self::parseTextViaId($html, $id, $tag)) {
            $response = str_replace('.', '', $response);

            return (int) $response;
        }

        return null;
    }

    public static function parseFloatViaId(string $html, string $id, string $tag = 'span'): ?float
    {
        if ($response = self::parseTextViaId($html, $id, $tag)) {
            return (float) $response;
        }

        return null;
    }

    public static function parseTextViaId(string $html, string $id, string $tag = 'span'): string
    {
        //         <$tag id="$id" class="mainValueAmount simpleTextFit">7483</$tag>
        preg_match("|id=\"$id\"[^>]*>([^<]+)</$tag>|", $html, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        preg_match("|id='$id'[^>]*>([^<]+)</$tag>|", $html, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return '';
    }

    public static function parseRegexFromHtml(string $html, string $pattern): string
    {
        preg_match($pattern, $html, $matches);

        return $matches[1] ?? '';
    }
}
