--TEST--
With a test case that sleeps in assertPreConditions() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version06/TestCase/WithAssertPreConditions/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version06/TestCase/WithAssertPreConditions/phpunit.xml

...                                                                 3 / 3 (100%)

Detected 3 tests where the duration exceeded the maximum duration.

1. 00:00.4%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\TestCase\WithAssertPreConditions\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1
2. 00:00.3%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\TestCase\WithAssertPreConditions\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0
3. 00:00.1%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\TestCase\WithAssertPreConditions\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
