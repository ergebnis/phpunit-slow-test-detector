--TEST--
With configuration setting stderr to true
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit12/Console/Configuration/Stderr/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the global maximum duration (0.050).

 # Duration Test
---------%s
 1    0.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(600)
 2    0.5%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(550)
 3    0.5%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(500)
 4    0.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(450)
 5    0.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(400)
 6    0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(350)
 7    0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(300)
 8    0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(250)
 9    0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(200)
10    0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\Stderr\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(150)
---------%s
      0.000
       └─── seconds

There is 1 additional slow test that is not listed here.

Time: %s
%a
