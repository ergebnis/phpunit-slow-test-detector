--TEST--
Configuring "maximum-count" parameter to 3 and "maximum-duration" parameter to 50 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/CustomConfiguration/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %Stest/EndToEnd/Version10/CustomConfiguration/phpunit.xml
Random %seed:   %s

.............                                                     13 / 13 (100%)

Detected 7 tests that took longer than expected.

1. 0.2%s (0.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromSlowThresholdAnnotation
2. 0.2%s (0.180) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotationWhenSlowThresholdAnnotationIsPresentBeforeMaximumDuration
3. 0.1%s (0.160) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotationWhenSlowThresholdAnnotationIsPresentAfterMaximumDuration

There are 4 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (13 tests, 13 assertions)
