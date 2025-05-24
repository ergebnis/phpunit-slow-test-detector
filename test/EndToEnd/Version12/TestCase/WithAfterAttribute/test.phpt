--TEST--
With a test case that sleeps in a method with After attribute
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version12/TestCase/WithAfterAttribute/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 3 tests where the duration exceeded the maximum duration.

1. 00:00.4%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\TestCase\WithAfterAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2. 00:00.3%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\TestCase\WithAfterAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
3. 00:00.1%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\TestCase\WithAfterAttribute\SleeperTest::testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration

%a
