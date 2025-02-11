--TEST--
With a test case that sleeps in data provider, hook, and test methods and has test methods with RunInSeparateProcess attribute
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version12/TestMethod/WithRunInSeparateProcessAttribute/phpunit.xml';

/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/main/src/Framework/TestRunner.php#L280-L282
 */
define('PHPUNIT_COMPOSER_INSTALL', __DIR__ . '/../../../../../vendor/autoload.php');

require_once PHPUNIT_COMPOSER_INSTALL;

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version12/TestMethod/WithRunInSeparateProcessAttribute/phpunit.xml

....                                                                4 / 4 (100%)

Detected 4 tests where the duration exceeded the maximum duration.

1. 1.3%s s (100 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute
2. 1.0%s s (100 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute
3.  8%s ms (100 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration
4.  6%s ms (100 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
