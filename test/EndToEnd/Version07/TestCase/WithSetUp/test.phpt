--TEST--
With a test case that sleeps in a setUp() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version07/TestCase/WithSetUp/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version07/TestCase/WithSetUp/phpunit.xml

...                                                                 3 / 3 (100%)

Detected 3 tests that took longer than expected.

1. 0.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2. 0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
3. 0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version07\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
