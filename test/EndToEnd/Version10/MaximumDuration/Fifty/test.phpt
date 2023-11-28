--TEST--
Configuring "maximum-duration" parameter to 50 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/MaximumDuration/Fifty/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: %Stest/EndToEnd/Version10/MaximumDuration/Fifty/phpunit.xml
Random Seed:   %s

.............                                                     13 / 13 (100%)

Detected 7 tests that took longer than expected.

1. 0.2%s (0.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromSlowThresholdAnnotation
2. 0.2%s (0.180) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotationWhenSlowThresholdAnnotationIsPresentBeforeMaximumDuration
3. 0.1%s (0.160) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotationWhenSlowThresholdAnnotationIsPresentAfterMaximumDuration
4. 0.1%s (0.160) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAttribute
5. 0.1%s (0.150) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotation
6. 0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsWithDocBlockWithMaximumDurationAnnotationWhereValueIsNotAnInt
7. 0.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation

Time: %s, Memory: %s

OK (13 tests, 13 assertions)
