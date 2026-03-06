--TEST--
With a test case that sleeps in a method with AfterClass attribute
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit13/Console/TestCase/WithAfterClassAttribute/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 2 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
---------%s
1    0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\PHPUnit13\TestCase\WithAfterClassAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(300)
2    0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\PHPUnit13\TestCase\WithAfterClassAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(200)
---------%s
     0.000
      └─── seconds

Time: %s
%a
