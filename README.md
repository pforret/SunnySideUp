# solar monitoring site data retrieval

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pforret/sunnysideup.svg?style=flat-square)](https://packagist.org/packages/pforret/sunnysideup)
[![Tests](https://img.shields.io/github/actions/workflow/status/pforret/sunnysideup/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/pforret/sunnysideup/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/pforret/sunnysideup.svg?style=flat-square)](https://packagist.org/packages/pforret/sunnysideup)

![](assets/unsplash.sunny.jpg)

works for
* [x] FusionSolar (Huawei) - Kiosk URL
* [x] SunnyPortal (SMA Solar)
* [ ] ...

## Installation

You can install the package via composer:

```bash
composer require pforret/sunnysideup
```

## Usage

```php
$sunny = new SunnySideUpClass();
$response = $sunny::get("https://region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html#/kiosk?kk=$id");
```

## Retrieved data

### Huawei/FusionSolar Kiosk URL

* Example URL: [region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html#/kiosk?kk=(unique ID)](https://region04eu5.fusionsolar.huawei.com/pvmswebsite/nologin/assets/build/index.html#/kiosk?kk=fo0x7vgtd9Noeqj9FHx2ofD0fPvAyj9b)
* Data:

```json
{
    "stationData": {
        "url": "https:\/\/region04eu5.fusionsolar.huawei.com\/rest\/pvms\/web\/kiosk\/v1\/station-kiosk-file?kk=fo0x7vgtd9Noeqj9FHx2ofD0fPvAyj9b",
        "name": "Forret",
        "id": "NE=35479375",
        "address": "Populierendallaan 3, 1850 Grimbergen, Belgium",
        "city": null,
        "country": null,
        "timezone": null,
        "panel_count": null,
        "watt_peak": null,
        "date_commissioning": null
    },
    "dayWeather": {
        "timeSunrise": null,
        "timeSunset": null,
        "maxTemperature": null,
        "currentTemperature": null,
        "currentPrecipitation": null
    },
    "currentData": {
        "currentPowerKw": 0.47,
        "timeSampled": null
    },
    "dayProduction": {
        "kwhSystem": 1.63,
        "equivalentTrees": null,
        "equivalentKgCoal": null,
        "equivalentKgCo2": null
    },
    "monthProduction": {
        "kwhSystem": 86.97,
        "equivalentTrees": null,
        "equivalentKgCoal": null,
        "equivalentKgCo2": null
    },
    "yearProduction": {
        "kwhSystem": 118.23,
        "equivalentTrees": null,
        "equivalentKgCoal": null,
        "equivalentKgCo2": null
    },
    "totalProduction": {
        "kwhSystem": 173.61,
        "equivalentTrees": null,
        "equivalentKgCoal": null,
        "equivalentKgCo2": null
    }
}
```

### SMA/SunnyPortal.com URL
* Example URL: [www.sunnyportal.com/Templates/PublicPageOverview.aspx?page=3e371bac-b19a-4257-853c-aac4d3601c0b&plant=46e9985f-128a-4da8-a70d-e95f72085ca4&splang=en-US](https://www.sunnyportal.com/Templates/PublicPageOverview.aspx?page=3e371bac-b19a-4257-853c-aac4d3601c0b&plant=46e9985f-128a-4da8-a70d-e95f72085ca4&splang=en-US)
* data:
```json
{
    "stationData": {
        "url": "https:\/\/www.sunnyportal.com\/Templates\/PublicPageOverview.aspx?page=3e371bac-b19a-4257-853c-aac4d3601c0b&plant=46e9985f-128a-4da8-a70d-e95f72085ca4&splang=en-US",
        "name": null,
        "id": null,
        "address": null,
        "city": null,
        "country": null,
        "timezone": "Romance Standard Time",
        "panel_count": null,
        "watt_peak": 1800,
        "date_commissioning": ""
    },
    "dayWeather": {
        "timeSunrise": null,
        "timeSunset": null,
        "maxTemperature": null,
        "currentTemperature": null,
        "currentPrecipitation": null
    },
    "currentData": {
        "currentPowerKw": 138,
        "timeSampled": "2023-02-24T09:45:00"
    },
    "dayProduction": {
        "kwhSystem": 0.077,
        "equivalentTrees": null,
        "equivalentKgCoal": null,
        "equivalentKgCo2": 0.054
    },
    "monthProduction": {
        "kwhSystem": null,
        "equivalentTrees": null,
        "equivalentKgCoal": null,
        "equivalentKgCo2": null
    },
    "yearProduction": {
        "kwhSystem": null,
        "equivalentTrees": null,
        "equivalentKgCoal": null,
        "equivalentKgCo2": null
    },
    "totalProduction": {
        "kwhSystem": 16385,
        "equivalentTrees": null,
        "equivalentKgCoal": null,
        "equivalentKgCo2": 11000
    }
}
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Peter Forret](https://github.com/pforret)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
