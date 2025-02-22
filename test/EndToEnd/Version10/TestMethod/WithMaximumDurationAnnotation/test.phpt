--TEST--
With test methods with @maximumDuration annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/TestMethod/WithMaximumDurationAnnotation/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version10/TestMethod/WithMaximumDurationAnnotation/phpunit.xml

....                                                                4 / 4 (100%)

Detected 2 tests where the duration exceeded the maximum duration.

1. 00:00.3%s (00:00.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithMaximumDurationAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasValidMaximumDurationAnnotation
2. 00:00.2%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithMaximumDurationAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidMaximumDurationAnnotation

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
