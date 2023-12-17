--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/DefaultConfiguration/phpunit.xml';

/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Framework/TestRunner.php#L288-L290
 */
define('PHPUNIT_COMPOSER_INSTALL', __DIR__ . '/../../../../vendor/autoload.php');

require_once PHPUNIT_COMPOSER_INSTALL;

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %Stest/EndToEnd/Version10/DefaultConfiguration/phpunit.xml
Random %seed:   %s

.......................                                           23 / 23 (100%)

Detected 12 tests that took longer than expected (500 ms).

 1. 1.2%d%d s (1.150 s) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAttributeWithValidMaximumDurationAndSlowThresholdAnnotation
 2. 1.1%d%d s (1.100 s) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAttribute
 3. 1.1%d%d s (1.000 s) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidSlowThresholdAndMaximumDurationAnnotation
 4. 1.0%d%d s (1.000 s) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAndSlowThresholdAnnotation
 5. 1.0%d%d s ( 900 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanSlowThresholdFromAnnotationWithValidSlowThresholdAnnotation
 6.  9%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidSlowThresholdAnnotation
 7.  9%d%d ms ( 800 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAnnotation
 8.  8%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidMaximumDurationAnnotation
 9.  8%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithUselessDocBlock
10.  7%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWhenRunningInSeparateProcess

There are 2 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (23 tests, 23 assertions)
