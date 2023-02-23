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

    public function testParseRegexFromHtml()
    {
        $html = '<span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldValue" class="mainValueAmount simpleTextFit">7483</span>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldUnit" class="mainValueUnit">Wh</span><br/>
        <span id="ctl00_ContentPlaceHolder1_PublicPagePlaceholder_PageUserControl_ctl00_PublicPageLoadFixPage_energyYieldWidget_energyYieldPeriodTitle" class="mainValueDescription">Today</span>';
        $this->assertEquals('Wh', SunnyPortalSource::parseRegexFromHtml($html, '|class="mainValueUnit">(\w+)</span>|'));
    }

    public function testGet()
    {
        $url = 'https://www.sunnyportal.com/Templates/PublicPageOverview.aspx?page=3e371bac-b19a-4257-853c-aac4d3601c0b&plant=46e9985f-128a-4da8-a70d-e95f72085ca4&splang=en-US';
        $response = (new SunnyPortalSource())->get($url);
        //print_r($response);
        $this->assertNotEmpty($response->stationData);
        $this->assertNotEmpty($response->dayProduction->kwhSystem);
    }
}
