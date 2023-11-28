--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version9/Default/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: %Stest/EndToEnd/Version9/Default/phpunit.xml
Random Seed:   %s

............                                                      12 / 12 (100%)

Detected 11 tests that took longer than expected.

 1. 1.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #10 (1050)
 2. 1.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #9 (1000)
 3. 0.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #8 (950)
 4. 0.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #7 (900)
 5. 0.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #6 (850)
 6. 0.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #5 (800)
 7. 0.7%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (750)
 8. 0.7%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (700)
 9. 0.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (650)
10. 0.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (600)

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (12 tests, 12 assertions)
