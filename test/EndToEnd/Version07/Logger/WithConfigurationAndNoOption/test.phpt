--TEST--
Logger with configuration and no option
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version07/Logger/WithConfigurationAndNoOption/phpunit.xml';

// https://github.com/php/php-src/issues/11104
if (!\defined('STDOUT')) {
    \define('STDOUT', \fopen('php://stdout', 'wb'));
    \define('STDERR', \fopen('php://stderr', 'wb'));
}

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version07/Logger/WithConfigurationAndNoOption/phpunit.xml

......                                                              6 / 6 (100%)

Detected 6 tests where the duration exceeded the maximum duration.

1. 1.0%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\Logger\WithConfigurationAndNoOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000)
2. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\Logger\WithConfigurationAndNoOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900)
3. 0.8%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\Logger\WithConfigurationAndNoOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800)
4. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\Logger\WithConfigurationAndNoOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (700)
5. 0.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\Logger\WithConfigurationAndNoOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #0 (600)
6. 0.5%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\Logger\WithConfigurationAndNoOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDuration

Time: %s, Memory: %s

OK (6 tests, 6 assertions)
