--TEST--
With custom configuration setting the "maximum-width" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit06/Console/Configuration/MaximumWidth/phpunit.xml';

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

......                                                              6 / 6 (100%)

Detected 5 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
----------------------------------------------------------------------------------------------------
1    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…nfigurationWithDataProvider with data set #4
2    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…nfigurationWithDataProvider with data set #3
3    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…nfigurationWithDataProvider with data set #2
4    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…nfigurationWithDataProvider with data set #1
5    %s Ergebnis\PHPUnit\SlowTestDetector\Test\EndTo…nfigurationWithDataProvider with data set #0
----------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

Time: %s
%a
