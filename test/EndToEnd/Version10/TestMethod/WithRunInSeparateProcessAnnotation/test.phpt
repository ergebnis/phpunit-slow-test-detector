--TEST--
With a test case that sleeps in data provider, hook, and test methods and has test methods with @runInSeparateProcess annotation
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/TestMethod/WithRunInSeparateProcessAnnotation/phpunit.xml';

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
Configuration: %s/EndToEnd/Version10/TestMethod/WithRunInSeparateProcessAnnotation/phpunit.xml

....                                                                4 / 4 (100%)

Detected 4 tests where the duration exceeded the maximum duration.

1. 1.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation
2. 1.0%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation
3. 1.0%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration
4. 0.8%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
