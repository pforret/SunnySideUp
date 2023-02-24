<?php

namespace Pforret\SunnySideUp\Tests\Sources;

use Pforret\SunnySideUp\Exceptions\InvalidContentError;
use Pforret\SunnySideUp\Exceptions\InvalidUrlError;
use Pforret\SunnySideUp\Sources\FusionSolarSource;
use PHPUnit\Framework\TestCase;

class FusionSolarSourceTest extends TestCase
{
    /**
     * @throws InvalidUrlError
     * @throws InvalidContentError
     */
    public function testGet()
    {
        $url = 'https://region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html#/kiosk?kk=fo0x7vgtd9Noeqj9FHx2ofD0fPvAyj9b';
        $sunny = new FusionSolarSource();
        $response = $sunny->get($url);
        //print_r(json_encode($response));

        $this->assertNotEmpty($response->stationData->name);
    }

    /**
     * @throws InvalidUrlError
     */
    public function testGetKey()
    {
        $url = 'https://region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html#/kiosk?kk=fo0x7vgtd9Noeqj9FHx2ofD0fPvAyj9b';
        $this->assertEquals('fo0x7vgtd9Noeqj9FHx2ofD0fPvAyj9b', FusionSolarSource::getKey($url));
        $this->assertEquals('fo0x7vgtd9Noeqj9FHx2ofD0fPvAyj9b', FusionSolarSource::getKey("$url&secondkey=value"));
    }
}
