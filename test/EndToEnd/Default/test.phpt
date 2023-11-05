--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Default/phpunit.xml';

require_once __DIR__ . '/../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: %Stest/EndToEnd/Default/phpunit.xml
Random Seed:   %s

.............                                                     13 / 13 (100%)

Detected 11 tests that took longer than expected.

 1. 1.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#9
 2. 1.5%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#8
 3. 1.4%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#7
 4. 1.3%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#6
 5. 1.2%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#5
 6. 1.1%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#4
 7. 1.0%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
 8. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2
 9. 0.8%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1
10. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#0

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (13 tests, 13 assertions)
