--TEST--
Logger with configuration and no option
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/Logger/WithConfigurationAndNoOption/phpunit.xml';
$_SERVER['argv'][] = '--no-output';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
