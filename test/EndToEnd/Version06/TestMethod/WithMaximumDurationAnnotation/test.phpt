--TEST--
With test methods with @maximumDuration annotations
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version06/TestMethod/WithMaximumDurationAnnotation/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version06/TestMethod/WithMaximumDurationAnnotation/phpunit.xml

....                                                                4 / 4 (100%)

Detected 2 tests where the duration exceeded the maximum duration (00:00.100).

1. 00:00.3%s (00:00.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\TestMethod\WithMaximumDurationAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasValidMaximumDurationAnnotation
2. 00:00.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\TestMethod\WithMaximumDurationAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidMaximumDurationAnnotation

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
