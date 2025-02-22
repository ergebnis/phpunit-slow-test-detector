--TEST--
With a test case that sleeps in data provider, hook, and test methods and has test methods with @runInSeparateProcess annotation
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version09/TestMethod/WithRunInSeparateProcessAnnotation/phpunit.xml';

/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/00:09.0.0/src/Framework/TestCase.php#L706-L708
 */
define('PHPUNIT_COMPOSER_INSTALL', __DIR__ . '/../../../../../vendor/autoload.php');

require_once PHPUNIT_COMPOSER_INSTALL;

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version09/TestMethod/WithRunInSeparateProcessAnnotation/phpunit.xml

....                                                                4 / 4 (100%)

Detected 4 tests where the duration exceeded the maximum duration.

1. 00:01.3%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation
2. 00:01.0%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation
3. 00:00.8%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration
4. 00:00.6%s (00:00.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version09\TestMethod\WithRunInSeparateProcessAnnotation\SleeperTest::testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration

Time: %s, Memory: %s

OK (4 tests, 4 assertions)
