--TEST--
With a test case that has a setUp() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version09/TestCase/WithSetUp/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version09/TestCase/WithSetUp/phpunit.xml
Random %seed:   %s

...                                                                 3 / 3 (100%)

Detected 3 tests that took longer than expected.

1. 0.4%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2. 0.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
3. 0.1%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestCase\WithSetUp\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
