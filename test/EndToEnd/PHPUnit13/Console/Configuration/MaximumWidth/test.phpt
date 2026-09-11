--TEST--
With custom configuration setting the "maximum-width" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit13/Console/Configuration/MaximumWidth/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

......                                                              6 / 6 (100%)

Detected 5 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
----------------------------------------------------------------------------------------------------
1    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…%s(350)
2    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…%s(300)
3    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…%s(250)
4    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…%s(200)
5    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…%s(150)
----------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
