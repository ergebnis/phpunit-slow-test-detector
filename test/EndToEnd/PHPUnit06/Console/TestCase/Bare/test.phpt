--TEST--
With a test case that does not sleep in methods that are not test methods
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit06/Console/TestCase/Bare/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

Detected 2 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\PHPUnit06\TestCase\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1
2    0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\PHPUnit06\TestCase\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
