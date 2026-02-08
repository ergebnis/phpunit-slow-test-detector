--TEST--
With a test case that sleeps in a setUp() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit08/TestCase/WithSetUp/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 3 tests where the duration exceeded the global maximum duration (00:00.100).

1. 00:00.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2. 00:00.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
3. 00:00.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit08\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

%a
