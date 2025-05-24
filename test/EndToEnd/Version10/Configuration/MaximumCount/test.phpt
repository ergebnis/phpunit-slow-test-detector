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
%a

......                                                              6 / 6 (100%)

Detected 5 tests where the duration exceeded the maximum duration.

1. 00:01.0%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000)
2. 00:00.9%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900)
3. 00:00.8%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800)

There are 2 additional slow tests that are not listed here.

%a
