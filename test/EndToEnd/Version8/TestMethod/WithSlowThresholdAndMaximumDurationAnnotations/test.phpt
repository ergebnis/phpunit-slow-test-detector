--TEST--
With @maximumDuration and @slowThreshold annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version8/TestMethod/WithSlowThresholdAndMaximumDurationAnnotations/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version8/TestMethod/WithSlowThresholdAndMaximumDurationAnnotations/phpunit.xml
Random %seed:   %s

..                                                                  2 / 2 (100%)

Detected 1 test that took longer than expected.

1. 0.3%s (0.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version8\TestMethod\WithSlowThresholdAndMaximumDurationAnnotations\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasMaximumDurationAndSlowThresholdAnnotations

Time: %s, Memory: %s

OK (2 tests, 2 assertions)
