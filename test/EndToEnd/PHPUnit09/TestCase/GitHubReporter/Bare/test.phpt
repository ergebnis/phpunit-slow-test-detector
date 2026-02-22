--TEST--
With a test case that sleeps longer than the maximum duration configured in GitHub Actions
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit09/TestCase/GitHubReporter/Bare/phpunit.xml';

\putenv('GITHUB_ACTIONS=true');

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

...                                                                 3 / 3 (100%)

Detected 2 tests where the duration exceeded the global maximum duration (0.100).

# Duration Test
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    0.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\TestCase\GitHubReporter\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
2    0.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\TestCase\GitHubReporter\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200)
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds
::warning title=Slow Test::Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\TestCase\GitHubReporter\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300) took 0.3%s, maximum allowed is 0.100
::warning title=Slow Test::Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\TestCase\GitHubReporter\Bare\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #0 (200) took 0.2%s, maximum allowed is 0.100

Time: %s
%a
