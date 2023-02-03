--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

putenv('MAXIMUM_NUMBER=10');

$_SERVER['argv'][] = '--configuration=test/EndToEnd/WithMaximumNumber10/phpunit.xml';

require_once __DIR__ . '/../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: test/EndToEnd/WithMaximumNumber10/phpunit.xml
Random Seed:   %s

.........                                                           9 / 9 (100%)

Detected 8 tests that took longer than expected.

1,0%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber10\SleeperTest::testSleeperSleepsOneSecond
  5%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber10\SleeperTest::testSleeperSleepsWithSlowThresholdAnnotation#1
  4%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber10\SleeperTest::testSleeperSleepsWithDocBlockWithSlowThresholdAnnotationWhereValueIsNotAnInt
  4%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber10\SleeperTest::testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation
  3%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber10\SleeperTest::testSleeperSleepsThreeHundredMilliseconds
  2%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber10\SleeperTest::testSleeperSleepsWithSlowThresholdAnnotation#0
  2%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber10\SleeperTest::testSleeperSleepsTwoHundredMilliseconds
  1%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber10\SleeperTest::testSleeperSleepsOneHundredFiftyMilliseconds

Time: %s, Memory: %s

OK (9 tests, 9 assertions)
