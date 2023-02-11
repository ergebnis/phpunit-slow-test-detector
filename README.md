# phpunit-slow-test-detector

[![Integrate](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Integrate/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Merge](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Merge/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Release](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Release/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Renew](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Renew/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/phpunit-slow-test-detector/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/phpunit-slow-test-detector)
[![Type Coverage](https://shepherd.dev/github/ergebnis/phpunit-slow-test-detector/coverage.svg)](https://shepherd.dev/github/ergebnis/phpunit-slow-test-detector)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/phpunit-slow-test-detector/v/stable)](https://packagist.org/packages/ergebnis/phpunit-slow-test-detector)
[![Total Downloads](https://poser.pugx.org/ergebnis/phpunit-slow-test-detector/downloads)](https://packagist.org/packages/ergebnis/phpunit-slow-test-detector)
[![Monthly Downloads](http://poser.pugx.org/ergebnis/phpunit-slow-test-detector/d/monthly)](https://packagist.org/packages/ergebnis/phpunit-slow-test-detector)

Provides an extension for detecting slow tests in [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit).

## Installation

Run

```sh
composer require --dev ergebnis/phpunit-slow-test-detector
```

## Usage

### Bootstrapping the extension

To bootstrap the extension, adjust your `phpunit.xml` configuration file:

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
 >
+    <extensions>
+        <bootstrap class="Ergebnis\PHPUnit\SlowTestDetector\Extension"/>
+    </extensions>
     <testsuites>
         <testsuite name="unit">
             <directory>test/Unit/</directory>
         </testsuite>
     </testsuites>
 </phpunit>
```

### Configuring the extension

You can configure the extension with the following parameters in your `phpunit.xml` configuration file:

- `maximum-count`, an `int`, the maximum count of slow test that should be listed, defaults to `10`
- `maximum-duration`, an `int`, the maximum duration in milliseconds for all tests, defaults to `500`

The following example configures the maximum count of slow tests to three, and the maximum duration for all tests to 250 milliseconds:

```xml
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
    bootstrap="vendor/autoload.php"
>
    <extensions>
        <bootstrap class="Ergebnis\PHPUnit\SlowTestDetector\Extension">
            <parameter name="maximum-count" value="3"/>
            <parameter name="maximum-duration" value="250"/>
        </boostrap>
    </extensions>
    <testsuites>
        <testsuite name="unit">
            <directory>test/Unit/</directory>
       </testsuite>
    </testsuites>
</phpunit>
```

#### Configuring the maximum duration per test case

You can configure the maximum duration for a single test with a `@maximumDuration` (or `@slowThreshold`) annotation in the DocBlock.

The following example configures the maximum duration for a single test to 5.000 ms:

```php
<?php

declare(strict_types=1);

use PHPUnit\Framework;

final class ExtraSlowTest extends Framework\TestCase
{
    /**
     * @maximumDuration 5000
     */
    public function testExtraExtraSlow(): void
    {
        // ...
    }

    /**
     * @slowThreshold 4000
     */
    public function testAlsoQuiteSlow(): void
    {
        // ...
    }
}
```

### Running tests

When you have activated the extension, you can run your tests as usually:

```sh
vendor/bin/phpunit
```

When the extension has detected slow tests, it will report them:

```txt
PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.0
Configuration: test/EndToEnd/Default/phpunit.xml
Random Seed:   1676103726

.............                                                                                                                                                                                                                                                                                                   13 / 13 (100%)

Detected 11 tests that took longer than expected.

1.605 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#9
1.505 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#8
1.401 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#7
1.301 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#6
1.200 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#5
1.105 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#4
1.000 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
0.903 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2
0.802 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1
0.703 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#0

There is one additional slow test that is not listed here.

Time: 00:12.601, Memory: 8.00 MB

OK (13 tests, 13 assertions)
```

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Credits

This project is inspired by [`johnkary/phpunit-speedtrap`](https://github.com/johnkary/phpunit-speedtrap).

## Curious what I am up to?

Follow me on [Twitter](https://twitter.com/intent/follow?screen_name=localheinz)!
