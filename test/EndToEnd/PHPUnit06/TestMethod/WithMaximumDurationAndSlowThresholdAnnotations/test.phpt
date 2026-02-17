--TEST--
With @maximumDuration and @slowThreshold annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit06/TestMethod/WithMaximumDurationAndSlowThresholdAnnotations/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

..                                                                  2 / 2 (100%)

Detected 1 test where the duration exceeded a custom or the global maximum duration (00:00.100).

# Duration            Test
  Actual    Maximum
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1 00:00.3%s 00:00.200 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit06\TestMethod\WithMaximumDurationAndSlowThresholdAnnotations\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasMaximumDurationAndSlowThresholdAnnotations

%a
