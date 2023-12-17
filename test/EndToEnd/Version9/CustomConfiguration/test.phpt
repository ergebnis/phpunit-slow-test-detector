--TEST--
Configuring "maximum-count" parameter to 3 and "maximum-duration" parameter to 300 milliseconds
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version9/CustomConfiguration/phpunit.xml';

require_once __DIR__ . '/../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %Stest/EndToEnd/Version9/CustomConfiguration/phpunit.xml
Random %seed:   %s

.....                                                               5 / 5 (100%)

Detected 5 tests that took longer than expected (300 ms).

1. 5%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #4 (500)
2. 4%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #3 (450)
3. 4%d%d ms Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\CustomConfiguration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #2 (400)

There are 2 additional slow tests that are not listed here.

Time: %s, Memory: %s

OK (5 tests, 5 assertions)
