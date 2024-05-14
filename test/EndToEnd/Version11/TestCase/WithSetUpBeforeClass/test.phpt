--TEST--
With a test case that sleeps in a setUpBeforeClass() method
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version11/TestCase/WithSetUpBeforeClass/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version11/TestCase/WithSetUpBeforeClass/phpunit.xml

...                                                                 3 / 3 (100%)

Detected 2 tests where the duration exceeded the maximum duration.

1. 0.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestCase\WithSetUpBeforeClass\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2. 0.2%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestCase\WithSetUpBeforeClass\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)

Time: %s, Memory: %s

OK (3 tests, 3 assertions)
