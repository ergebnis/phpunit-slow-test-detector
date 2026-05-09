--TEST--
With custom configuration setting the "maximum-duration" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit08/Console/Configuration/MaximumDuration/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the global maximum duration (0.050).

 # Duration Test
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 1    0.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #10 (600)
 2    0.5%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #9 (550)
 3    0.5%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #8 (500)
 4    0.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #7 (450)
 5    0.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #6 (400)
 6    0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #5 (350)
 7    0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #4 (300)
 8    0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #3 (250)
 9    0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #2 (200)
10    0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\Console\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (150)
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
      0.000
       └─── seconds

There is 1 additional slow test that is not listed here.

Time: %s
%a
