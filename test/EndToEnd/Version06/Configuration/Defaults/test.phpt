--TEST--
With default configuration
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version06/Configuration/Defaults/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version06/Configuration/Defaults/phpunit.xml

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the maximum duration.

 1. 1.6%s s (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #10
 2. 1.5%s s (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #9
 3. 1.4%s s (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #8
 4. 1.3%s s (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #7
 5. 1.2%s s (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #6
 6. 1.1%s s (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #5
 7. 1.0%s s (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4
 8.  9%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3
 9.  8%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2
10.  7%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (12 tests, 12 assertions)
