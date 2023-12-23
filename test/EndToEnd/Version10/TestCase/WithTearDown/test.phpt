--TEST--
With a test case that sleeps in a tearDown() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/TestCase/WithTearDown/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version10/TestCase/WithTearDown/phpunit.xml

...                                                                 3 / 3 (100%)

Detected 3 tests that took longer than expected.

1. 0.4%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\WithTearDown\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#1
2. 0.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\WithTearDown\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#0
3. 0.1%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\WithTearDown\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
