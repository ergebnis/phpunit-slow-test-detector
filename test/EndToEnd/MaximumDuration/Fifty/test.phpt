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

.......                                                             7 / 7 (100%)

Detected 5 tests that took longer than expected.

1%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromSlowThresholdAnnotation
1%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsWithDocBlockWithSlowThresholdAnnotationWhereValueIsNotAnInt
 9%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromSlowThresholdAnnotation
 8%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation
 6%s ms (50 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsJustAboveDefaultMaximumDuration

Time: %s, Memory: %s

OK (7 tests, 7 assertions)
