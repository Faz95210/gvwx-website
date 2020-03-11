<?php

error_reporting(E_ALL | E_STRICT);

require_once 'PHPUnit/TextUI/TestRunner.php';
require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

// Add the services file to the default service builder
$servicesFile = dirname(__FILE__) . '/Guzzle/Tests/TestData/services/services.json';
Guzzle\Tests\GuzzleTestCase::setServiceBuilder(Guzzle\Service\Builder\ServiceBuilder::factory($servicesFile));
