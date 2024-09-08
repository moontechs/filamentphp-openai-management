# OpenAI files and batches management 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/moontechs/openai-batches-management.svg?style=flat-square)](https://packagist.org/packages/moontechs/openai-batches-management)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/moontechs/openai-batches-management/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/moontechs/openai-batches-management/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/moontechs/openai-batches-management/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/moontechs/openai-batches-management/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/moontechs/openai-batches-management.svg?style=flat-square)](https://packagist.org/packages/moontechs/openai-batches-management)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require moontechs/openai-batches-management
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="openai-batches-management-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="openai-batches-management-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="openai-batches-management-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$openaiBatchesManagement = new Moontechs\OpenaiBatchesManagement();
echo $openaiBatchesManagement->echoPhrase('Hello, Moontechs!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Moontechs](https://github.com/moontechs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
