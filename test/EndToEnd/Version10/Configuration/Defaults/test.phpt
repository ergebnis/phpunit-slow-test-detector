--TEST--
With default configuration
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/Configuration/Defaults/phpunit.xml';
$_SERVER['argv'][] = '--random-order-seed=1234567890';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version10/Configuration/Defaults/phpunit.xml
Random %seed:   %s

............                                                      12 / 12 (100%)

Detected 11 tests that took longer than expected.

 1. 1.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#10
 2. 1.5%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#9
 3. 1.4%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#8
 4. 1.3%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#7
 5. 1.2%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#6
 6. 1.1%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#5
 7. 1.0%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#4
 8. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
 9. 0.8%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2
10. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (12 tests, 12 assertions)
