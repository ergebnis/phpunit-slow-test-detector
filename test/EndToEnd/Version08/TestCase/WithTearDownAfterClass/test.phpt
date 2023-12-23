--TEST--
With a test case that sleeps in a tearDownAfterClass() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version08/TestCase/WithTearDownAfterClass/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version08/TestCase/WithTearDownAfterClass/phpunit.xml

...                                                                 3 / 3 (100%)

Detected 2 tests that took longer than expected.

1. 0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\TestCase\WithTearDownAfterClass\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2. 0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\TestCase\WithTearDownAfterClass\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
