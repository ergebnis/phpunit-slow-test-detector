--TEST--
Configuring "maximum-count" parameter to 3 and "maximum-duration" parameter to 300 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version11/Configuration/WithCustomConfiguration/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %Stest/EndToEnd/Version11/Configuration/WithCustomConfiguration/phpunit.xml
Random %seed:   %s

.....                                                               5 / 5 (100%)

Detected 5 tests that took longer than expected.

1. 0.5%s (0.300) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\WithCustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#4
2. 0.4%s (0.300) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\WithCustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#3
3. 0.4%s (0.300) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\WithCustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#2

There are 2 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (5 tests, 5 assertions)
