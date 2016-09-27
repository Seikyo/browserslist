# Browserslist

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

**Browserslist** is a tool that reports on current browsers based on data pulled from [caniuse's browser statistics](https://github.com/Fyrd/caniuse). 
This project is a PHP port of [the JS library of the same name](https://github.com/ai/browserslist).

## Install

Via Composer

``` bash
$ composer require buttress/browserslist
```

## Usage

``` php
$listFactory = new BrowserslistFactory();
$browserslist = $listFactory->createWithData();

// Get the last version of every browser AND browsers with > 10% usage
$browsers = $browserslist('last 1 version, > 10%');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email korvinszanto@gmail.com instead of using the issue tracker.

## Credits

- [Korvin Szanto][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/buttress/browserslist.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/buttress/browserslist/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Buttress/browserslist.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Buttress/browserslist.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Buttress/browserslist.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Buttress/browserslist
[link-travis]: https://travis-ci.org/buttress/browserslist
[link-scrutinizer]: https://scrutinizer-ci.com/g/Buttress/browserslist/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Buttress/browserslist
[link-downloads]: https://packagist.org/packages/Buttress/browserslist
[link-author]: https://github.com/korvinszanto
[link-contributors]: ../../contributors
