--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version9/DefaultConfiguration/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %Stest/EndToEnd/Version9/DefaultConfiguration/phpunit.xml
Random %seed:   %s

...................                                               19 / 19 (100%)

Detected 11 tests that took longer than expected.

 1. 1.2%s (1.000) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidSlowThresholdAndMaximumDurationAnnotation
 2. 1.1%s (1.000) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAndSlowThresholdAnnotation
 3. 1.1%s (0.900) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanSlowThresholdFromAnnotationWithValidSlowThresholdAnnotation
 4. 1.0%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidSlowThresholdAnnotation
 5. 1.0%s (0.800) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAnnotation
 6. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidMaximumDurationAnnotation
 7. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithUselessDocBlock
 8. 0.8%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDuration
 9. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (600)
10. 0.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\DefaultConfiguration\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (550)

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (19 tests, 19 assertions)
