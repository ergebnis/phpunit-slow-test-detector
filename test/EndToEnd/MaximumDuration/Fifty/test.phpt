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

Detected 4 tests that took longer than expected.

0.1%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromSlowThresholdAnnotation
0.1%s (0.050) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsWithDocBlockWithSlowThresholdAnnotationWhereValueIsNotAnInt
0.0%s (0.050) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation
0.0%s (0.050) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumDuration\Fifty\SleeperTest::testSleeperSleepsJustAboveDefaultMaximumDuration

Time: %s, Memory: %s

OK (7 tests, 7 assertions)
