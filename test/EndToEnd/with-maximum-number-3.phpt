--TEST--
With default configuration of extension
--FILE--
<?php

declare(strict_types=1);

putenv('MAXIMUM_NUMBER=3');

$_SERVER['argv'][] = '--configuration=test/EndToEnd/WithMaximumNumber3/phpunit.xml';

require_once __DIR__ . '/../../vendor/autoload.php';

PHPUnit\TextUI\Application::main();
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Runtime: %s
Configuration: test/EndToEnd/WithMaximumNumber3/phpunit.xml
Random Seed:   %s

.........                                                           9 / 9 (100%)

Time: %s, Memory: %s

OK (9 tests, 9 assertions)
