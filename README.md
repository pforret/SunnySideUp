# solar monitoring site data retrieval

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pforret/sunnysideup.svg?style=flat-square)](https://packagist.org/packages/pforret/sunnysideup)
[![Tests](https://img.shields.io/github/actions/workflow/status/pforret/sunnysideup/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/pforret/sunnysideup/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/pforret/sunnysideup.svg?style=flat-square)](https://packagist.org/packages/pforret/sunnysideup)

![](assets/unsplash.sunny.jpg)

works for
* [x] FusionSolar (Huawei) - Kiosk URL
* [ ] SunnyPortal (SMA Solar)
* ...

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
