--TEST--
With a test case that has setUpBeforeClass(), tearDownAfterClass(), setUp(), assertPreConditions(), assertPostConditions(), tearDown() methods and test methods with RunInSeparateProcess attribute
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version11/TestMethod/WithRunInSeparateProcessAttribute/phpunit.xml';

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
Configuration: %s/EndToEnd/Version11/TestMethod/WithRunInSeparateProcessAttribute/phpunit.xml

....                                                                4 / 4 (100%)

Detected 4 tests that took longer than expected.

1. 0.4%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute
2. 0.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration
3. 0.1%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration
4. 0.1%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
