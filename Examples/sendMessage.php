<?php
require __DIR__ . '/../vendor/autoload.php';

use NikoPeikrishvili\TwilioMinimal\TwilioConfig;
use NikoPeikrishvili\TwilioMinimal\TwilioMinimal;

$config = new TwilioConfig();
$config->setSenderNumber("");
$config->setClientSid("");
$config->setAuthToken("");

$twilio = new TwilioMinimal($config);

$twilio->send("+00000000000", "Messge body");