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

.........                                                           9 / 9 (100%)

Detected 7 tests that took longer than expected.

1,2%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#5
1,1%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#4
1,0%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
  9%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2
  8%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumCount\Five\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1

There are 2 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (9 tests, 9 assertions)
