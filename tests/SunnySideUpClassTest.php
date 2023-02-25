<?php

namespace Pforret\SunnySideUp\Tests;

use Pforret\SunnySideUp\Exceptions\InvalidContentError;
use Pforret\SunnySideUp\Exceptions\InvalidUrlError;
use Pforret\SunnySideUp\Exceptions\UnknownSiteError;
use Pforret\SunnySideUp\SunnySideUp;
use PHPUnit\Framework\TestCase;

class SunnySideUpClassTest extends TestCase
{
    /**
     * @throws UnknownSiteError
     * @throws InvalidContentError
     * @throws InvalidUrlError
     */
    public function testGetUnknownSite()
    {
        $sunny = new SunnySideUp();
        $this->expectException(UnknownSiteError::class);
        $response = $sunny::get('https://www.nonsense.com');
    }

    /**
     * @throws InvalidContentError
     * @throws InvalidUrlError
     * @throws UnknownSiteError
     */
    public function testGetFake()
    {
        $sunny = new SunnySideUp();
        $response = $sunny::get('https://www.example.com');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->totalProduction);
    }

    /**
     * @throws InvalidContentError
     * @throws InvalidUrlError
     * @throws UnknownSiteError
     */
    public function testGetFusionSolar()
    {
        $sunny = new SunnySideUp();
        $response = $sunny::get('https://region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html#/kiosk?kk=fo0x7vgtd9Noeqj9FHx2ofD0fPvAyj9b');
        //print_r($response);
        $this->assertNotEmpty($response);

        $this->assertNotEmpty($response->stationData);
        $this->assertNotEmpty($response->stationData->url);

        $this->assertNotEmpty($response->dayProduction);
        $this->assertNotEmpty($response->dayProduction->kwhSystem);
    }

    /**
     * @throws InvalidUrlError
     * @throws InvalidContentError
     * @throws UnknownSiteError
     */
    public function testGetSunnyPortal()
    {
        $sunny = new SunnySideUp();
        $response = $sunny::get('https://www.sunnyportal.com/Templates/PublicPageOverview.aspx?page=3e371bac-b19a-4257-853c-aac4d3601c0b&plant=46e9985f-128a-4da8-a70d-e95f72085ca4&splang=en-US');
        $this->assertNotEmpty($response);

        $this->assertNotEmpty($response->stationData);
        $this->assertNotEmpty($response->stationData->url);

        $this->assertNotEmpty($response->dayProduction);
        $this->assertNotEmpty($response->dayProduction->kwhSystem);
    }

    public function testTopDomainSunnyPortal()
    {
        $sunny = new SunnySideUp();
        $this->assertEquals('sunnyportal.com', $sunny::topDomain('https://www.sunnyportal.com/Templates/PublicPageOverview.aspx'));
    }

    public function testTopDomainFusionSolar()
    {
        $sunny = new SunnySideUp();
        $this->assertEquals('fusionsolar.huawei.com', $sunny::topDomain('https://region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html'));
    }

    public function testTopDomainFakeSource()
    {
        $sunny = new SunnySideUp();
        $this->assertEquals('example.com', $sunny::topDomain('https://www.example.com'));
    }
}
