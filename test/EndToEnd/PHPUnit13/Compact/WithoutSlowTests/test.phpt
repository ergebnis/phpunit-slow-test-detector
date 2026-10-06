--TEST--
With compact output and no tests exceeding the global maximum duration
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
$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit13/Compact/WithoutSlowTests/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a
OK (1 test, 1 assertion)
