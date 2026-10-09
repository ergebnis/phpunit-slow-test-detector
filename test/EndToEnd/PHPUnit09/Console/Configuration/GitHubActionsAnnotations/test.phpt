--TEST--
With custom configuration setting the "github-actions-annotations" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit09/Console/Configuration/GitHubActionsAnnotations/phpunit.xml';

\putenv('GITHUB_WORKSPACE=' . \realpath(__DIR__ . '/../../../../../..'));

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

......                                                              6 / 6 (100%)

Detected 5 tests where the duration exceeded the global maximum duration (0.500).

# Duration Test
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
1    1.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000)
2    0.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900)
3    0.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800)
4    0.7%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (700)
5    0.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #0 (600)
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     0.000
      └─── seconds

::warning title=Slow Test,file=test/EndToEnd/PHPUnit09/Console/Configuration/GitHubActionsAnnotations/SleeperTest.php,line=38::Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000) took 1.0%s seconds, maximum is 0.500 seconds
::warning title=Slow Test,file=test/EndToEnd/PHPUnit09/Console/Configuration/GitHubActionsAnnotations/SleeperTest.php,line=38::Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900) took 0.9%s seconds, maximum is 0.500 seconds
::warning title=Slow Test,file=test/EndToEnd/PHPUnit09/Console/Configuration/GitHubActionsAnnotations/SleeperTest.php,line=38::Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800) took 0.8%s seconds, maximum is 0.500 seconds
::warning title=Slow Test,file=test/EndToEnd/PHPUnit09/Console/Configuration/GitHubActionsAnnotations/SleeperTest.php,line=38::Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (700) took 0.7%s seconds, maximum is 0.500 seconds
::warning title=Slow Test,file=test/EndToEnd/PHPUnit09/Console/Configuration/GitHubActionsAnnotations/SleeperTest.php,line=38::Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsAnnotations\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #0 (600) took 0.6%s seconds, maximum is 0.500 seconds


Time: %s
%a
