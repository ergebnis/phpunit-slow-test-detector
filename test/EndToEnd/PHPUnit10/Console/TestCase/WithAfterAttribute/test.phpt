--TEST--
With a test case that sleeps in a method with After attribute
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit10/Console/TestCase/WithAfterAttribute/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 3 tests where the duration exceeded the global maximum duration (0.050).

# Duration Test
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Console\TestCase\WithAfterAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (150)
2    0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Console\TestCase\WithAfterAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (100)
3    0.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Console\TestCase\WithAfterAttribute\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
