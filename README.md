# ApproveDeny SDK for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ownage/approvedeny-sdk-php.svg?style=flat-square)](https://packagist.org/packages/ownage/approvedeny-sdk-php)
[![Total Downloads](https://img.shields.io/packagist/dt/ownage/approvedeny-sdk-php.svg?style=flat-square)](https://packagist.org/packages/ownage/approvedeny-sdk-php)
![GitHub Actions](https://github.com/Ownage-FDT/approvedeny-sdk-php/actions/workflows/run-tests.yml/badge.svg)

The ApproveDeny SDK for PHP provides an easy way to interact with the ApproveDeny API using PHP.

## Installation
> **Requires [PHP 8.1+](https://php.net/releases/)**

You can install the package via composer:

```bash
composer require ownage/approvedeny-sdk-php
```

## Usage
To use the SDK, you need to create an instance of the approvedeny `Client` class. You can do this by passing your API key to the constructor.

```php
use Ownage\ApproveDeny\Client;

$client = new Client('your-api-key');
```

### Creating a new check request
To create a new check request, you need to call the `createCheckRequest` method on the client instance.
```php
$checkRequest = $client->createCheckRequest('check-id', [
    'description' => 'A description of the check request',  
    'metadata' => [
        'key' => 'value',
    ],
]);
```

### Retrieving a check request
To retrieve a check request, you need to call the `getCheckRequest` method on the client instance.
```php
$checkRequest = $client->getCheckRequest('check-request-id');
```

### Retrieving a check request response
To retrieve a check request response, you need to call the `getCheckRequestResponse` method on the client instance.
```php
$checkRequestResponse = $client->getCheckRequestResponse('check-request-id');
```

### Verifying webhook signatures
To verify webhook signatures, you need to call the `isValidWebhookSignature` method on the client instance. This method returns a boolean value indicating whether the signature is valid or not.

```php

$isValidSignature = $client->isValidWebhookSignature('your-encryption-key', 'signature', ['foo' => 'bar']);

if ($isValidSignature) {
    // The signature is valid
} else
    // The signature is invalid
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please use the issue tracker.

## Credits

-   [Olayemi Olatayo](https://github.com/iamolayemi)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
