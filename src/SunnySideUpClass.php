<?php

namespace Pforret\SunnySideUp;

use Pforret\SunnySideUp\Exceptions\InvalidContentError;
use Pforret\SunnySideUp\Exceptions\InvalidUrlError;
use Pforret\SunnySideUp\Exceptions\UnknownSiteError;
use Pforret\SunnySideUp\Formats\ProductionResponse;
use Pforret\SunnySideUp\Sources\FakeSource;
use Pforret\SunnySideUp\Sources\FusionSolarSource;
use Pforret\SunnySideUp\Sources\SourceInterface;
use Pforret\SunnySideUp\Sources\SunnyPortalSource;

class SunnySideUpClass
{
    private SourceInterface $source;

    /**
     * @throws InvalidContentError
     * @throws InvalidUrlError
     * @throws UnknownSiteError
     */
    public static function get(string $url): ProductionResponse
    {
        $domain = self::topDomain($url);
        $source = match ($domain) {
            'example.com' => new FakeSource(),
            'sunnyportal.com' => new SunnyPortalSource(),
            'fusionsolar.huawei.com' => new FusionSolarSource(),
            default => null,
        };
        if (! $source) {
            throw new UnknownSiteError();
        }

        return $source->get($url);
    }

    public static function topDomain(string $url): string
    {
        $domain = parse_url($url, PHP_URL_HOST);
        $domain = str_replace('www.', '', $domain);
        $parts = explode('.', $domain);
        if (count($parts) <= 3) {
            return $domain;
        }

        return implode('.', array_slice($parts, -3));
    }
}
