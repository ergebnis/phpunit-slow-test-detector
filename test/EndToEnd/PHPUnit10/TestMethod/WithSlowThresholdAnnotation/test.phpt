--TEST--
With test methods with @slowThreshold annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit10/TestMethod/WithSlowThresholdAnnotation/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

....                                                                4 / 4 (100%)

Detected 2 tests where the duration exceeded a custom or the global maximum duration (0.100).

# Duration          Test
  Actual   Maximum
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    0.3%s    0.200 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\TestMethod\WithSlowThresholdAnnotation\SleeperTest::testSleeperSleepsLongerThanSlowThresholdFromAnnotationWhenTestMethodHasValidSlowThresholdAnnotation
2    0.2%s          Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\TestMethod\WithSlowThresholdAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidSlowThresholdAnnotation
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
