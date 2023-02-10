--TEST--
Configuring "maximum-duration" parameter to 50 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/MaximumCount/Five/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: test/EndToEnd/MaximumCount/Five/phpunit.xml
Random Seed:   %s

.......                                                             7 / 7 (100%)

Detected 6 tests that took longer than expected.

5%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
5%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2
5%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1
5%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#0
5%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsJustAboveDefaultMaximumDuration

There is one additional slow test that is not listed here.

Time: %s, Memory: %s

OK (7 tests, 7 assertions)
