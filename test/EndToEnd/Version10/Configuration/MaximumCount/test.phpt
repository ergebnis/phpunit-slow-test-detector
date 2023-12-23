--TEST--
With custom configuration setting the "maximum-count" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/Configuration/MaximumCount/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version10/Configuration/MaximumCount/phpunit.xml

......                                                              6 / 6 (100%)

Detected 5 tests that took longer than expected.

1. 1.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#4
2. 0.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
3. 0.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2

There are 2 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (6 tests, 6 assertions)
