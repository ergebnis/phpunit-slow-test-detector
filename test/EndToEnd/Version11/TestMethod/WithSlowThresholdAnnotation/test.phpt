--TEST--
With test methods with @slowThreshold annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version11/TestMethod/WithSlowThresholdAnnotation/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version11/TestMethod/WithSlowThresholdAnnotation/phpunit.xml

....                                                                4 / 4 (100%)

Detected 2 tests where the duration exceeded the maximum duration (00:00.100).

1. 00:00.3%s (00:00.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestMethod\WithSlowThresholdAnnotation\SleeperTest::testSleeperSleepsLongerThanSlowThresholdFromAnnotationWhenTestMethodHasValidSlowThresholdAnnotation
2. 00:00.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestMethod\WithSlowThresholdAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidSlowThresholdAnnotation

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
