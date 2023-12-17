--TEST--
Configuring "maximum-count" parameter to 3 and "maximum-duration" parameter to 300 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/CustomConfiguration/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %Stest/EndToEnd/Version10/CustomConfiguration/phpunit.xml
Random %seed:   %s

.....                                                               5 / 5 (100%)

Detected 5 tests that took longer than expected (300 ms).

1. 5%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#4
2. 4%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#3
3. 4%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#2

There are 2 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (5 tests, 5 assertions)
