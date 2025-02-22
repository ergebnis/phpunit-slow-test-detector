--TEST--
With @maximumDuration and @slowThreshold annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version06/TestMethod/WithSlowThresholdAndMaximumDurationAnnotations/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version06/TestMethod/WithSlowThresholdAndMaximumDurationAnnotations/phpunit.xml

..                                                                  2 / 2 (100%)

Detected 1 test where the duration exceeded the maximum duration.

1. 00:00.3%s (00:00.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\TestMethod\WithSlowThresholdAndMaximumDurationAnnotations\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasMaximumDurationAndSlowThresholdAnnotations

Time: %s, Memory: %s

OK (2 tests, 2 assertions)
