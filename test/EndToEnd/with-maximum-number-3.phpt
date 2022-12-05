--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

putenv('MAXIMUM_NUMBER=3');

$_SERVER['argv'][] = '--configuration=test/EndToEnd/WithMaximumNumber3/phpunit.xml';

require_once __DIR__ . '/../../vendor/autoload.php';

PHPUnit\TextUI\Application::main();
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: test/EndToEnd/WithMaximumNumber3/phpunit.xml
Random Seed:   %s

.........                                                           9 / 9 (100%)

Detected 8 tests that took longer than expected.

1,0%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber3\SleeperTest::testSleeperSleepsOneSecond
  5%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber3\SleeperTest::testSleeperSleepsWithSlowThresholdAnnotation#1
  4%s ms (125 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\WithMaximumNumber3\SleeperTest::testSleeperSleepsWithDocBlockWithSlowThresholdAnnotationWhereValueIsNotAnInt

There are 5 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (9 tests, 9 assertions)
