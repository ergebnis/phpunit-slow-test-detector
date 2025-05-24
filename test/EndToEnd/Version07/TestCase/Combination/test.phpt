--TEST--
With a test case that sleeps in data provider, hook, and test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version07/TestCase/Combination/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 3 tests where the duration exceeded the maximum duration.

1. 00:00.9%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\TestCase\Combination\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2. 00:00.8%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\TestCase\Combination\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
3. 00:00.6%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\TestCase\Combination\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

%a
