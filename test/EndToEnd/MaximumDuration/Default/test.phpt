--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/MaximumDuration/Default/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: test/EndToEnd/MaximumDuration/Default/phpunit.xml
Random Seed:   %s

.........                                                           9 / 9 (100%)

Detected 2 tests that took longer than expected.

1,0%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Default\SleeperTest::testSleeperSleepsOneSecond
  5%s ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Default\SleeperTest::testSleeperSleepsWithSlowThresholdAnnotation#1

Time: %s, Memory: %s

OK (9 tests, 9 assertions)
