--TEST--
With test methods with @maximumDuration annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit11/TestMethod/WithMaximumDurationAnnotation/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

......                                                              6 / 6 (100%)

Detected 2 tests where the duration exceeded a custom or the global maximum duration (00:00.100).

# Duration            Test
  Actual    Maximum
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1 00:00.3%s 00:00.200 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\TestMethod\WithMaximumDurationAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasValidMaximumDurationAnnotation
2 00:00.2%s           Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\TestMethod\WithMaximumDurationAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidMaximumDurationAnnotation

%a
