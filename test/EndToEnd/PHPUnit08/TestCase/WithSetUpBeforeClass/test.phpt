--TEST--
With a test case that sleeps in a setUpBeforeClass() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit08/TestCase/WithSetUpBeforeClass/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 2 tests where the duration exceeded the global maximum duration (00:00.100).

# Duration  Test
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1 00:00.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\TestCase\WithSetUpBeforeClass\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2 00:00.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\TestCase\WithSetUpBeforeClass\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

Time: %s
%a
