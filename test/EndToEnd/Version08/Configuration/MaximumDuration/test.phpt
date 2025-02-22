--TEST--
With custom configuration setting the "maximum-duration" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version08/Configuration/MaximumDuration/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version08/Configuration/MaximumDuration/phpunit.xml

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the maximum duration.

 1. 00:01.2%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #10 (1200)
 2. 00:01.1%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #9 (1100)
 3. 00:01.0%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #8 (1000)
 4. 00:00.9%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #7 (900)
 5. 00:00.8%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #6 (800)
 6. 00:00.7%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #5 (700)
 7. 00:00.6%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #4 (600)
 8. 00:00.5%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #3 (500)
 9. 00:00.4%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #2 (400)
10. 00:00.3%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (12 tests, 12 assertions)
