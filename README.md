# TwilioMinimal
[![Build Status](https://travis-ci.com/nikopeikrishvili/TwilioMinimal.svg?branch=master)](https://travis-ci.com/nikopeikrishvili/TwilioMinimal)
[![Coverage](https://codecov.io/gh/nikopeikrishvili/TwilioMinimal/branch/master/graph/badge.svg)](https://codecov.io/gh/nikopeikrishvili/TwilioMinimal)

Minimal Twilio package just for sending SMS Messages

## Supported PHP Versions
* 7.1
* 7.2
* 7.3
* 7.4

### Instalation Via Composer:

**TwilioMinimal** is available on Packagist as the
[`nikopeikrishvili/twiliominimal`](https://packagist.org/packages/nikopeikrishvili/twiliominimal) package:

```
composer require nikopeikrishvili/twiliominimal
```

## Quickstart

### Send an SMS

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use NikoPeikrishvili\TwilioMinimal\TwilioConfig;
use NikoPeikrishvili\TwilioMinimal\TwilioMinimal;

$config = new TwilioConfig();
// Sender phone number 
$config->setSenderNumber("");
// Client SID
$config->setClientSid("");
// Client Auth Token
$config->setAuthToken("");

$twilio = new TwilioMinimal($config);

$twilio->send("+00000000000", "Message body");
```

## Testing
```bash
composer test
```
## Coding style checks
```bash
composer cs
```


