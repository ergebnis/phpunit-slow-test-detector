--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/Configuration/WithDefaultConfiguration/phpunit.xml';

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
Configuration: %Stest/EndToEnd/Version10/Configuration/WithDefaultConfiguration/phpunit.xml
Random %seed:   %s

.......................                                           23 / 23 (100%)

Detected 12 tests that took longer than expected.

 1. 1.2%s (1.150) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAttributeWithValidMaximumDurationAndSlowThresholdAnnotation
 2. 1.1%s (1.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAttribute
 3. 1.1%s (1.000) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidSlowThresholdAndMaximumDurationAnnotation
 4. 1.0%s (1.000) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAndSlowThresholdAnnotation
 5. 1.0%s (0.900) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanSlowThresholdFromAnnotationWithValidSlowThresholdAnnotation
 6. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidSlowThresholdAnnotation
 7. 0.9%s (0.800) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAnnotation
 8. 0.8%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidMaximumDurationAnnotation
 9. 0.8%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithUselessDocBlock
10. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWhenRunningInSeparateProcess

There are 2 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (23 tests, 23 assertions)
