--TEST--
With a test case that sleeps in a method with BeforeClass attribute
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit10/Console/TestCase/WithBeforeClassAttribute/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 2 tests where the duration exceeded the global maximum duration (0.050).

# Duration Test
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Console\TestCase\WithBeforeClassAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (150)
2    0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\Console\TestCase\WithBeforeClassAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (100)
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
