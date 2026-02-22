--TEST--
With a test case that sleeps in data provider, hook, and test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit10/TestCase/ConsoleReporter/Combination/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 3 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    1.%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\TestCase\ConsoleReporter\Combination\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2    1.%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\TestCase\ConsoleReporter\Combination\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
3    0.%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\TestCase\ConsoleReporter\Combination\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
