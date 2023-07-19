# Laravel OpenaiAttribute

Allows defining OpenAI generated attributes for Laravel model.

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

- Install package
``` bash
 composer require dmsemenov/openai-attribute
```
- Add your OpenAI api key `OPENAI_API_KEY` to `.env`.    
Key can be found here https://platform.openai.com/account/api-keys

## Configuration
 
Publish config if needed:
``` bash
 php artisan vendor:publish --provider="Dmsemenov\OpenaiAttribute\OpenaiAttributeServiceProvider"
```
It is possible to specify request options in published config or in the model `generatedAttributes()` method.  
Available request options see https://platform.openai.com/docs/api-reference/chat/create
``` php
    // OpenAI api key
    'api_key' => env('OPENAI_API_KEY'),

    // Queue name for generate attributes jobs
    'queue_name' => env('OPENAI_QUEUE_NMAE', 'openai_generate'),

    // Default options for api chat completions requests.
    'default_options' => [
        'model' => env('OPENAI_API_MODEL', 'gpt-3.5-turbo'),
        'temperature' => 0.3,
        'max_tokens' => 100,
        'top_p' => 1.0,
        'frequency_penalty' => 0.0,
        'presence_penalty' => 0.0
    ],
```

## Usage
Trait `HasGeneratedAttributes` should be added to model. it contains list of model attributes with prompt and options (if needed, see default_options):
``` php
public function generatedAttributes(): array
{
    return [
        'description' => [
            'prompt' => 'Tell info about [model:name]". Wrap each paragraph to tag <p>',
            'max_tokens' => 1000
        ],

        'slug' => [
            'prompt' => 'Generate slug from [model:name]',
            'max_tokens' => 10
        ],
    ];
}
```

Models that fits condition in `[MyModel]->NeedsGenerateAttributes()` will be selected for attributes generation. Default: all.

Artisan commands to generate attributes:
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
