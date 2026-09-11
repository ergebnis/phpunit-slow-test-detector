--TEST--
With default configuration
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit12/Console/Configuration/Defaults/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the global maximum duration (0.500).

 # Duration Test
---------%s
 1    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1050)
 2    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1000)
 3    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(950)
 4    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(900)
 5    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(850)
 6    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(800)
 7    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(750)
 8    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(700)
 9    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(650)
10    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(600)
---------%s
      0.000
       └─── seconds

There is 1 additional slow test that is not listed here.

Time: %s
%a
