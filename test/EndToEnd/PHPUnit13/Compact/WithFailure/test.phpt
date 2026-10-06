--TEST--
With compact output, a failing test, and a test exceeding the global maximum duration
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
$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit13/Compact/WithFailure/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a
FAILURES (2 tests, 2 assertions, 1 failure)

=== ergebnis/phpunit-slow-test-detector ===

--- SLOW: Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit13\Compact\WithFailure\SleeperTest::testSleeperSleepsLongerThanMaximumDuration
0.3%s seconds (maximum 0.100 seconds)

SLOW (1 test)
