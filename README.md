# FcPhp Context

Context to FcPhp Application. Autoload Context inside packages with cache using [FcPhp Cache](https://github.com/00F100/fcphp-cache) and [FcPhp Autoload](https://github.com/00F100/fcphp-autoload)

[![Build Status](https://travis-ci.org/00F100/fcphp-context.svg?branch=master)](https://travis-ci.org/00F100/fcphp-context) [![codecov](https://codecov.io/gh/00F100/fcphp-context/branch/master/graph/badge.svg)](https://codecov.io/gh/00F100/fcphp-context) [![Total Downloads](https://poser.pugx.org/00F100/fcphp-context/downloads)](https://packagist.org/packages/00F100/fcphp-context)

## How to install

Composer:
```sh
$ composer require 00f100/fcphp-context
```

or add in composer.json
```json
{
	"require": {
		"00f100/fcphp-context": "*"
	}
}
```

## How to use

```php
<?php
/**
 * Construct instance of Context
 *
 * @param array $context Context to apply
 * @return void
 */
$context = new Context(array $context = []);
```

## Example

```php
<?php

use FcPhp\Context\Context;

$context = [
	'cache' => [
		'file' => 'tests/var/cache',
	]
];

$context = new Context($context);

// Print
// tests/var/cache
echo $context->get('cache.file');
```

## Autoload, update Context and cache

```php
<?php

use FcPhp\Context\Context;
use FcPhp\Autoload\Autoload;
use FcPhp\Cache\Facades\CacheFacade;

$context = [
	'cache' => [
		'file' => 'tests/var/cache',
	]
];

$context = new Context($context);

$vendorPath = 'vendor/*/*/config';
$autoload = new Autoload();
$cache = CacheFacade::getInstance('tests/var/cache');

$context->autoload($vendorPath, $autoload, $cache);
```