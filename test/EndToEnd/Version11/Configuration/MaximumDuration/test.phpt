--TEST--
With custom configuration setting the "maximum-duration" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version11/Configuration/MaximumDuration/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version11/Configuration/MaximumDuration/phpunit.xml

............                                                      12 / 12 (100%)

Detected 11 tests that took longer than expected.

 1. 1.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#10
 2. 1.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#9
 3. 1.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#8
 4. 0.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#7
 5. 0.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#6
 6. 0.7%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#5
 7. 0.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#4
 8. 0.5%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#3
 9. 0.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#2
10. 0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider#1

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (12 tests, 12 assertions)
