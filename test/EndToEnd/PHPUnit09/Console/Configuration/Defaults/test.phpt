--TEST--
With default configuration
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit09/Console/Configuration/Defaults/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the global maximum duration (0.500).

 # Duration Test
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 1    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #10 (1050)
 2    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #9 (1000)
 3    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #8 (950)
 4    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #7 (900)
 5    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #6 (850)
 6    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #5 (800)
 7    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (750)
 8    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (700)
 9    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (650)
10    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (600)
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
      0.000
       └─── seconds

There is 1 additional slow test that is not listed here.

Time: %s
%a
