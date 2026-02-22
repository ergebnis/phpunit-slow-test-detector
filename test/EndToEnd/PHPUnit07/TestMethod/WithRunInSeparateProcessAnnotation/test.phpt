--TEST--
With a test case that sleeps in data provider, hook, and test methods and has test methods with @runInSeparateProcess annotation
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit07/TestMethod/WithRunInSeparateProcessAnnotation/phpunit.xml';

/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/7.5.0/src/Framework/TestCase.php#L728-L730
 */
define('PHPUNIT_COMPOSER_INSTALL', __DIR__ . '/../../../../../vendor/autoload.php');

require_once PHPUNIT_COMPOSER_INSTALL;

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

....                                                                4 / 4 (100%)

Detected 4 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1 %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit07\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation
2 %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit07\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation
3 %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit07\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration
4 %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit07\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
