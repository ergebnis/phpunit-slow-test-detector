--TEST--
Configuring "maximum-duration" parameter to 50 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/MaximumCount/Three/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: %Stest/EndToEnd/Version10/MaximumCount/Three/phpunit.xml
Random Seed:   %s

.....                                                               5 / 5 (100%)

Detected 4 tests that took longer than expected.

1. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumCount\Three\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
2. 0.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumCount\Three\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2
3. 0.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumCount\Three\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (5 tests, 5 assertions)
