--TEST--
With default configuration
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version13/Configuration/Defaults/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the maximum duration.

 1. 00:01.6%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1600)
 2. 00:01.5%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1500)
 3. 00:01.4%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1400)
 4. 00:01.3%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1300)
 5. 00:01.2%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1200)
 6. 00:01.1%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1100)
 7. 00:01.0%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(1000)
 8. 00:00.9%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(900)
 9. 00:00.8%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(800)
10. 00:00.7%s (00:00.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider%s(700)

There is 1 additional slow test that is not listed here.

%a
