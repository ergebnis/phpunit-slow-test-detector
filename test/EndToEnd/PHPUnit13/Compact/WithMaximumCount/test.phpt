--TEST--
With compact output, the "maximum-count" parameter, and more slow tests than the maximum count
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
$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit13/Compact/WithMaximumCount/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a
OK (3 tests, 3 assertions)

=== ergebnis/phpunit-slow-test-detector ===

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithMaximumCount\SleeperTest::testSleeperSleepsLongerThanMaximumDurationWithDataProvider%s(400)
0.4%s seconds (maximum 0.100 seconds)

SLOW (3 tests, 2 not listed)
