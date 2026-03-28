--TEST--
With a test case that does not sleep in methods that are not test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit12/Console/TestCase/Bare/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 2 tests where the duration exceeded the global maximum duration (0.050).

# Duration Test
---------%s
1    0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\TestCase\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(150)
2    0.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\TestCase\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(100)
---------%s
     0.000
      └─── seconds

Time: %s
%a
