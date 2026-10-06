--TEST--
With compact output, without the "maximum-count" parameter, and more slow tests than the default maximum count
--SKIPIF--
<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../../vendor/autoload.php';

if (\version_compare(PHPUnit\Runner\Version::id(), '13.2.0', '<')) {
    echo 'skip: compact output requires phpunit/phpunit:^13.2.0';
}
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--compact';
$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit13/Compact/WithoutMaximumCount/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a
OK (11 tests, 11 assertions)

=== ergebnis/phpunit-slow-test-detector ===

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(1100)
1.1%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(1000)
1.0%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(900)
0.9%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(800)
0.8%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(700)
0.7%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(600)
0.6%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(500)
0.5%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(400)
0.4%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(300)
0.3%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(200)
0.2%s seconds (maximum 0.050 seconds)

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithoutMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(100)
0.1%s seconds (maximum 0.050 seconds)

SLOW (11 tests)
