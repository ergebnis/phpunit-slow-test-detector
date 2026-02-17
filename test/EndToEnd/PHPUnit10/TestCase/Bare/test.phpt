--TEST--
With a test case that does not sleep in methods that are not test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit10/TestCase/Bare/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 2 tests where the duration exceeded the global maximum duration (00:00.100).

# Duration  Test
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1 00:00.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\TestCase\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2 00:00.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit10\TestCase\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)

%a
