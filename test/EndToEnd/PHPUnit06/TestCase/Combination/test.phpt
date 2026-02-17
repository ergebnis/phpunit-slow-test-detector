--TEST--
With a test case that sleeps in data provider, hook, and test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit06/TestCase/Combination/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a
...                                                                 3 / 3 (100%)

Detected 3 tests where the duration exceeded the global maximum duration (00:00.100).

# Duration  Test
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1 00:00.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit06\TestCase\Combination\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1
2 00:00.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit06\TestCase\Combination\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0
3 00:00.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit06\TestCase\Combination\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

%a
