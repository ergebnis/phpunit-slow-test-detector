--TEST--
With custom configuration setting the "github-actions-step-summary" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit09/Console/Configuration/GitHubActionsStepSummary/phpunit.xml';

$gitHubStepSummary = \tempnam(
    \sys_get_temp_dir(),
    'github-step-summary',
);

\putenv('GITHUB_STEP_SUMMARY=' . $gitHubStepSummary);

\register_shutdown_function(static function () use ($gitHubStepSummary): void {
    echo \file_get_contents($gitHubStepSummary);

    \unlink($gitHubStepSummary);
});

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
%a

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the global maximum duration (0.500).

 # Duration Test
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 1    1.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #10 (1600)
 2    1.5%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #9 (1500)
 3    1.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #8 (1400)
 4    1.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #7 (1300)
 5    1.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #6 (1200)
 6    1.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #5 (1100)
 7    1.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000)
 8    0.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900)
 9    0.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800)
10    0.7%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (700)
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
      0.000
       └─── seconds

There is 1 additional slow test that is not listed here.


Time: %s
%a

## Slow tests

Detected 11 tests where the duration exceeded the global maximum duration (0.500 seconds).

| # | Duration | Maximum | Test |
|--:|--:|--:|:--|
| 1 | 1.6%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #10 (1600)` |
| 2 | 1.5%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #9 (1500)` |
| 3 | 1.4%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #8 (1400)` |
| 4 | 1.3%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #7 (1300)` |
| 5 | 1.2%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #6 (1200)` |
| 6 | 1.1%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #5 (1100)` |
| 7 | 1.0%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000)` |
| 8 | 0.9%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900)` |
| 9 | 0.8%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800)` |
| 10 | 0.7%s | 0.500 | `Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit09\Console\Configuration\GitHubActionsStepSummary\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (700)` |

There is 1 additional slow test that is not listed here.
