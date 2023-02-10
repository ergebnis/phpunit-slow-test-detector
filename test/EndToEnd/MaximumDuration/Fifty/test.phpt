--TEST--
Configuring "maximum-duration" parameter to 50 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/MaximumDuration/Fifty/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: test/EndToEnd/MaximumDuration/Fifty/phpunit.xml
Random Seed:   %s

.........                                                           9 / 9 (100%)

Detected 8 tests that took longer than expected.

1,0%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsOneSecond
  5%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsWithSlowThresholdAnnotation#1
  4%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsWithDocBlockWithSlowThresholdAnnotationWhereValueIsNotAnInt
  4%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation
  3%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsThreeHundredMilliseconds
  2%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsWithSlowThresholdAnnotation#0
  2%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsTwoHundredMilliseconds
  1%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\SleeperTest::testSleeperSleepsOneHundredFiftyMilliseconds

Time: %s, Memory: %s

OK (9 tests, 9 assertions)
