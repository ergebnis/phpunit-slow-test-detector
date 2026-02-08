--TEST--
With default configuration
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit10/Configuration/Defaults/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the global maximum duration (00:00.500).

 1. 00:01.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #10 (1600)
 2. 00:01.5%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #9 (1500)
 3. 00:01.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #8 (1400)
 4. 00:01.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #7 (1300)
 5. 00:01.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #6 (1200)
 6. 00:01.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #5 (1100)
 7. 00:01.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000)
 8. 00:00.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900)
 9. 00:00.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800)
10. 00:00.7%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Configuration\Defaults\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (700)

There is 1 additional slow test that is not listed here.

%a
