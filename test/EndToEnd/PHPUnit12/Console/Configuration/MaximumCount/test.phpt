--TEST--
With custom configuration setting the "maximum-count" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit12/Console/Configuration/MaximumCount/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

......                                                              6 / 6 (100%)

Detected 5 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
---------%s
1    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(350)
2    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(300)
3    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit12\Console\Configuration\MaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider%s(250)
---------%s
     0.000
      └─── seconds

There are 2 additional slow tests that are not listed here.

Time: %s
%a
