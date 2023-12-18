--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version9/Configuration/WithDefaultConfiguration/phpunit.xml';

/**
 * @see https://github.com/sebastianbergmann/phpunit/blob/8.5.19/src/Framework/TestCase.php#L754C1-L756
 */
define('PHPUNIT_COMPOSER_INSTALL', __DIR__ . '/../../../../../vendor/autoload.php');

require_once PHPUNIT_COMPOSER_INSTALL;

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %Stest/EndToEnd/Version9/Configuration/WithDefaultConfiguration/phpunit.xml
Random %seed:   %s

.....................                                             21 / 21 (100%)

Detected 12 tests that took longer than expected.

 1. 1.2%s (1.000) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidSlowThresholdAndMaximumDurationAnnotation
 2. 1.1%s (1.000) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAndSlowThresholdAnnotation
 3. 1.1%s (0.900) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanSlowThresholdFromAnnotationWithValidSlowThresholdAnnotation
 4. 1.0%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWhenRunningInSeparateProcess
 5. 1.0%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidSlowThresholdAnnotation
 6. 1.0%s (0.800) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAnnotation
 7. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidMaximumDurationAnnotation
 8. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithUselessDocBlock
 9. 0.8%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDuration
10. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\Configuration\WithDefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (600)

There are 2 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (21 tests, 21 assertions)
