--TEST--
With a test case that has setUpBeforeClass(), tearDownAfterClass(), setUp(), assertPreConditions(), tearDown() methods and test methods with RunInSeparateProcess attribute
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/TestMethod/WithRunInSeparateProcessAttribute/phpunit.xml';

/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Framework/TestRunner.php#L288-L290
 */
define('PHPUNIT_COMPOSER_INSTALL', __DIR__ . '/../../../../../vendor/autoload.php');

require_once PHPUNIT_COMPOSER_INSTALL;

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version10/TestMethod/WithRunInSeparateProcessAttribute/phpunit.xml
Random %seed:   %s

....                                                                4 / 4 (100%)

Detected 2 tests that took longer than expected.

1. 0.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute
2. 0.2%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithRunInSeparateProcessAttribute\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
