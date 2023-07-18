# Laravel OpenaiAttribute

Allows defining OpenAI generated attributes for Laravel model.
Package currently support only `text-davinci-003` model but other options will be added soon.

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
 composer require dmsemenov/openai-attribute
```
Publish config if needed:

``` bash
 php artisan vendor:publish --provider="Dmsemenov\OpenaiAttribute\OpenaiAttributeServiceProvider"
```

## Usage
Models that fits condition in `[MyModel]->NeedsGenerateAttributes()` will be selected for attributes generation.

Generate commands:
``` bash
php artisan openai:generate App\\Models\\[MyModel] --queued
```
or generate instantly without queue option:
``` bash
php artisan openai:generate App\\Models\\[MyModel]
```
or specify model ids list:
``` bash
php artisan openai:generate App\\Models\\[MyModel] --ids=1 --ids=2
```
Other useful commands:
``` bash
Display queue size:
php artisan queue:monitor openai_generate

Remove jobs from queue:
php artisan queue:clear --queue=openai_generate

Run workers:
php artisan queue:work --queue=openai_generate
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email dimitr.semenov@gmail.com instead of using the issue tracker.

## Credits

- [Dmitry Semenov][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/dmsemenov/openai-attribute.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/dmsemenov/openai-attribute.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/dmsemenov/openai-attribute/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/dmsemenov/openai-attribute
[link-downloads]: https://packagist.org/packages/dmsemenov/openai-attribute
[link-travis]: https://travis-ci.org/dmsemenov/openai-attribute
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/dmsemenov
[link-contributors]: ../../contributors
