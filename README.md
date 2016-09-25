# Browserslist

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Install

Via Composer

``` bash
$ composer require Buttress/Browserslist
```

## Usage

``` php
$listFactory = new BrowserlistFactory();
$browserlist = $listFactory->createWithData();

// Get the last version of every browser AND browsers with > 10% usage
$browsers = $browserlist('last 1 version, > 10%');
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

[ico-version]: https://img.shields.io/packagist/v/Buttress/Browserslist.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Buttress/Browserslist/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Buttress/Browserslist.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Buttress/Browserslist.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Buttress/Browserslist.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Buttress/Browserslist
[link-travis]: https://travis-ci.org/Buttress/Browserslist
[link-scrutinizer]: https://scrutinizer-ci.com/g/Buttress/Browserslist/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Buttress/Browserslist
[link-downloads]: https://packagist.org/packages/Buttress/Browserslist
[link-author]: https://github.com/korvinszanto
[link-contributors]: ../../contributors
