--TEST--
With a test case where no test sleeps longer than the maximum duration from XML configuration
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version06/TestCase/WithoutSlowTests/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

Could not detect any tests where the duration exceeded the maximum duration.

%a
