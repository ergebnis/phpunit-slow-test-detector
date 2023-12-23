--TEST--
With a test case that sleeps in data provider, hook, and test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/TestCase/Combination/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version10/TestCase/Combination/phpunit.xml

...                                                                 3 / 3 (100%)

Detected 3 tests that took longer than expected.

1. 1.1%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\Combination\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#1
2. 1.0%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\Combination\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#0
3. 0.8%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\Combination\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
