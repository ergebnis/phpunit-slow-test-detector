--TEST--
With test methods with @maximumDuration annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit06/TestMethod/WithMaximumDurationAnnotation/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

......                                                              6 / 6 (100%)

Detected 2 tests where the duration exceeded a custom or the global maximum duration (0.100).

# Duration          Test
  Actual   Maximum
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    0.3%s    0.200 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit06\TestMethod\WithMaximumDurationAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasValidMaximumDurationAnnotation
2    0.2%s          Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit06\TestMethod\WithMaximumDurationAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidMaximumDurationAnnotation
---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
