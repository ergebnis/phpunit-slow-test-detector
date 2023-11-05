--TEST--
Configuring "maximum-duration" parameter to 50 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/MaximumCount/Five/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: %Stest/EndToEnd/MaximumCount/Five/phpunit.xml
Random Seed:   %s

.............                                                     13 / 13 (100%)

Detected 11 tests that took longer than expected.

1. 1.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#9
2. 1.5%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#8
3. 1.4%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#7
4. 1.3%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#6
5. 1.2%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#5

There are 6 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (13 tests, 13 assertions)
