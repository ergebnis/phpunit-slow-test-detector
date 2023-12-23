--TEST--
With a test case that sleeps in a method with @afterClass annotation
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version09/TestCase/WithAfterClassAnnotation/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version09/TestCase/WithAfterClassAnnotation/phpunit.xml

...                                                                 3 / 3 (100%)

Detected 2 tests where the duration exceeded the maximum duration.

1. 0.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestCase\WithAfterClassAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2. 0.2%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestCase\WithAfterClassAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
