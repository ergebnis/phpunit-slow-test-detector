--TEST--
With a test case that sleeps in assertPreConditions() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit08/Console/TestCase/WithAssertPreConditions/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 3 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    0.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\PHPUnit08\TestCase\WithAssertPreConditions\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2    0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\PHPUnit08\TestCase\WithAssertPreConditions\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
3    0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\PHPUnit08\TestCase\WithAssertPreConditions\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
