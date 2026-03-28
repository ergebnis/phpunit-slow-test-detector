--TEST--
With test methods with @maximumDuration Attributes
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit12/Console/TestMethod/WithMaximumDurationAttribute/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

..                                                                  2 / 2 (100%)

Detected 1 test where the duration exceeded a custom or the global maximum duration (0.050).

# Duration          Test
  Actual   Maximum
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    0.1%s    0.100 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\TestMethod\WithMaximumDurationAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAttributeWhenTestMethodHasValidMaximumDurationAttribute
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
