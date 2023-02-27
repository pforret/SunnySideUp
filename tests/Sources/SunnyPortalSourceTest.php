<?php

namespace Pforret\SunnySideUp\Tests\Sources;

use Pforret\SunnySideUp\Sources\SunnyPortalSource;
use PHPUnit\Framework\TestCase;

class SunnyPortalSourceTest extends TestCase
{
    public function testParseIntViaId()
    {
        $html = '<span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue" class="mainValueAmount simpleTextFit">7483</span>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldUnit" class="mainValueUnit">Wh</span><br/>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldPeriodTitle" class="mainValueDescription">Today</span>';

        $this->assertEquals(7483, SunnyPortalSource::parseIntViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue'));
        $this->assertNull(SunnyPortalSource::parseIntViaId($html, 'nonExistent'));
    }

    public function testParse0kwh()
    {
        $html = file_get_contents(__DIR__.'/data/sunnyportal_0kwh.html');
        $this->assertEquals(0, SunnyPortalSource::parseFloatViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue'));
        $this->assertNull(SunnyPortalSource::parseIntViaId($html, 'nonExistent'));
    }

    public function testParse77kwh()
    {
        $html = file_get_contents(__DIR__.'/data/sunnyportal_77kwh.html');
        $this->assertEquals(77, SunnyPortalSource::parseFloatViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue'));
        $this->assertNull(SunnyPortalSource::parseIntViaId($html, 'nonExistent'));
    }

    public function testParseFloatViaId()
    {
        $html = '<span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue" class="mainValueAmount simpleTextFit">0</span>
            <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldUnit" class="mainValueUnit">Wh</span><br/>
            <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldPeriodTitle" class="mainValueDescription">Today</span>';
        $this->assertEquals(0, SunnyPortalSource::parseFloatViaId($html, 'ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue'));
    }

    public function testParseRegexFromHtml()
    {
        $html = '<span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue" class="mainValueAmount simpleTextFit">7483</span>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldUnit" class="mainValueUnit">Wh</span><br/>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldPeriodTitle" class="mainValueDescription">Today</span>';
        $this->assertEquals('Wh', SunnyPortalSource::parseRegexFromHtml($html, '|class="mainValueUnit">(\w+)</span>|'));
    }

    public function testGet1()
    {
        $url = 'https://www.sunnyportal.com/Templates/PublicPageOverview.aspx?page=3e371bac-b19a-4257-853c-aac4d3601c0b&plant=46e9985f-128a-4da8-a70d-e95f72085ca4&splang=en-US';
        $response = (new SunnyPortalSource())->get($url);
        //print_r(json_encode($response));
        $this->assertNotEmpty($response->stationData, 'StationData not empty');
        $this->assertNotEmpty($response->dayProduction->kwhSystem, 'Daily Production not empty');
        $this->assertEquals(1800, $response->stationData->watt_peak);
    }

    public function testGet2()
    {
        $url = 'https://www.sunnyportal.com/Templates/PublicPageOverview.aspx?page=5f2f06bc-e7dd-48ec-9d0c-38e94bb6affb&plant=51f2f3e1-a960-45ce-9041-875963f205ee&splang=en-US';
        $response = (new SunnyPortalSource())->get($url);
        //print_r(json_encode($response));
        $this->assertNotEmpty($response->stationData, 'StationData not empty');
        $this->assertNotEmpty($response->dayProduction->kwhSystem, 'Daily Production not empty');
        $this->assertEquals(4200, $response->stationData->watt_peak);
    }

    public function testGetKwh()
    {
        $url = 'https://www.sunnyportal.com/Templates/PublicPageOverview.aspx?page=2ade58a5-7f70-413c-8cc2-1af7e841b4c0&plant=5c2312a8-2138-4466-941d-1fa07866f1f3&splang=en-US';
        $response = (new SunnyPortalSource())->get($url);
        //print_r(json_encode($response));
        $this->assertNotEmpty($response->stationData, 'StationData not empty');
        $this->assertNotEmpty($response->dayProduction->kwhSystem, 'Daily Production not empty');
        $this->assertEquals(10300, $response->stationData->watt_peak);
    }
}
