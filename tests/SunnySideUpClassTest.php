<?php

namespace Pforret\SunnySideUp\Tests;

use Pforret\SunnySideUp\Exceptions\UnknownSiteError;
use Pforret\SunnySideUp\SunnySideUpClass;
use PHPUnit\Framework\TestCase;

class SunnySideUpClassTest extends TestCase
{

    /**
     * @throws UnknownSiteError
     */
    public function testGetUnknownSite()
    {
        $sunny = new SunnySideUpClass();
        $this->expectException(UnknownSiteError::class);
        $response = $sunny::get("https://www.nonsense.com");
    }

    /**
     * @throws UnknownSiteError
     */
    public function testGetFake()
    {
        $sunny = new SunnySideUpClass();
        $response = $sunny::get("https://www.example.com");
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->totalProduction);
    }

    /**
     * @throws UnknownSiteError
     */
    public function testGetFusionSolar()
    {
        $sunny = new SunnySideUpClass();
        $response = $sunny::get("https://region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html#/kiosk?kk=fo0x7vgtd9Noeqj9FHx2ofD0fPvAyj9b");
        print_r($response);
        $this->assertNotEmpty($response);

        $this->assertNotEmpty($response->stationData);
        $this->assertNotEmpty($response->stationData->url);

        $this->assertNotEmpty($response->dayProduction);
        $this->assertNotEmpty($response->dayProduction->kwhSystem);
    }

    public function testTopDomainSunnyPortal()
    {
        $sunny = new SunnySideUpClass();
        $this->assertEquals("sunnyportal.com",$sunny::topDomain("https://www.sunnyportal.com/Templates/PublicPageOverview.aspx"));
    }
    public function testTopDomainFusionSolar()
    {
        $sunny = new SunnySideUpClass();
        $this->assertEquals("fusionsolar.huawei.com",$sunny::topDomain("https://region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html"));
    }
    public function testTopDomainFakeSource()
    {
        $sunny = new SunnySideUpClass();
        $this->assertEquals("example.com",$sunny::topDomain("https://www.example.com"));
    }
}
