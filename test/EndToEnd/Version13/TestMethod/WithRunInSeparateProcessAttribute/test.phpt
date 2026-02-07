--TEST--
With a test case that sleeps in data provider, hook, and test methods and has test methods with RunInSeparateProcess attribute
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version13/TestMethod/WithRunInSeparateProcessAttribute/phpunit.xml';

/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/main/src/Framework/TestRunner.php#L280-L282
 */
define('PHPUNIT_COMPOSER_INSTALL', __DIR__ . '/../../../../../vendor/autoload.php');

require_once PHPUNIT_COMPOSER_INSTALL;

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

....                                                                4 / 4 (100%)

Detected 4 tests where the duration exceeded the global maximum duration (00:00.100).

1. 00:01.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute
2. 00:01.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute
3. 00:00.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration
4. 00:00.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version13\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration

%a
