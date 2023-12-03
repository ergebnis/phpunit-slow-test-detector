--TEST--
Configuring "maximum-duration" parameter to 50 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version9/MaximumCount/Three/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %Stest/EndToEnd/Version9/MaximumCount/Three/phpunit.xml
Random %seed:   %s

.....                                                               5 / 5 (100%)

Detected 4 tests that took longer than expected.

1. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\MaximumCount\Three\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (700)
2. 0.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\MaximumCount\Three\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (650)
3. 0.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\MaximumCount\Three\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (600)

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (5 tests, 5 assertions)
