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

............                                                      12 / 12 (100%)

Detected 7 tests that took longer than expected.

1. 0.2%s (0.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromSlowThresholdAnnotation
2. 0.2%s (0.180) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotationWhenSlowThresholdAnnotationIsPresentBeforeMaximumDuration
3. 0.1%s (0.160) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotationWhenSlowThresholdAnnotationIsPresentAfterMaximumDuration
4. 0.1%s (0.150) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotation
5. 0.1%s (0.050) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsWithDocBlockWithMaximumDurationAnnotationWhereValueIsNotAnInt
6. 0.0%s (0.050) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation
7. 0.0%s (0.050) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsJustAboveDefaultMaximumDuration

Time: %s, Memory: %s

OK (12 tests, 12 assertions)
