--TEST--
With a test case that has no test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit11/TestCase/WithoutTestMethods/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

There was 1 PHPUnit test runner warning:

1) No tests found in class "Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\TestCase\WithoutTestMethods\SleeperTest".

No tests executed!
