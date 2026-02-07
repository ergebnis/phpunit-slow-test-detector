--TEST--
With test methods with @maximumDuration Attributes
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/TestMethod/WithMaximumDurationAttribute/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

..                                                                  2 / 2 (100%)

Detected 1 test where the duration exceeded a custom or the global maximum duration (00:00.100).

1. 00:00.3%s (00:00.200) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithMaximumDurationAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAttributeWhenTestMethodHasValidMaximumDurationAttribute

%a
