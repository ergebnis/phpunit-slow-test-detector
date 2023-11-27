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

This package provides an extension for detecting slow tests in [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit).

## Installation

### Composer

Run

```sh
composer require --dev ergebnis/phpunit-slow-test-detector
```

to install `ergebnis/phpunit-slow-test-detector` as a `composer` package.

### Phar

Download `phpunit-slow-test-detector.phar` from the [latest release](https://github.com/ergebnis/phpunit-slow-test-detector/releases/latest).

## Usage

### Bootstrapping the extension as a `composer` package

To bootstrap the extension as a `composer` package when using `phpunit/phpunit:^10.4.0`, adjust your `phpunit.xml` configuration file and configure the [`extensions` element](https://docs.phpunit.de/en/10.4/configuration.html#the-extensions-element):

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

To bootstrap the extension as a `composer` package when using `phpunit/phpunit:^9.6.0`, adjust your `phpunit.xml` configuration file and configure the [`extensions` element](https://docs.phpunit.de/en/9.6/configuration.html#the-extensions-element):

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
 >
+    <extensions>
+        <extension class="Ergebnis\PHPUnit\SlowTestDetector\Extension"/>
+    </extensions>
     <testsuites>
         <testsuite name="unit">
             <directory>test/Unit/</directory>
         </testsuite>
     </testsuites>
 </phpunit>
```

### Bootstrapping the extension as a PHAR

To bootstrap the extension as a PHAR when using `phpunit/phpunit:^10.4.0`, adjust your `phpunit.xml` configuration file and configure the [`extensionsDirectory` attribute](https://docs.phpunit.de/en/10.4/configuration.html#the-extensionsdirectory-attribute) of the [`<phpunit>` element](https://docs.phpunit.de/en/10.4/configuration.html#the-phpunit-element):

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
+    extensionsDirectory="directory/where/you/saved/the/extension/phars"
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

The following example configures the maximum count of slow tests to three, and the maximum duration for all tests to 250 milliseconds when using `phpunit/phpunit:^10.4.0`:

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
 >
     <extensions>
-        <bootstrap class="Ergebnis\PHPUnit\SlowTestDetector\Extension"/>
+        <bootstrap class="Ergebnis\PHPUnit\SlowTestDetector\Extension">
+            <parameter name="maximum-count" value="3"/>
+            <parameter name="maximum-duration" value="250"/>
+        </bootstrap>
     </extensions>
     <testsuites>
         <testsuite name="unit">
             <directory>test/Unit/</directory>
        </testsuite>
     </testsuites>
 </phpunit>
```

The following example configures the maximum count of slow tests to three, and the maximum duration for all tests to 250 milliseconds when using `phpunit/phpunit:^9.6.0`:

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
 >
     <extensions>
-        <extension class="Ergebnis\PHPUnit\SlowTestDetector\Extension"/>
+        <extension class="Ergebnis\PHPUnit\SlowTestDetector\Extension">
+            <arguments>
+                <array>
+                    <element key="maximum-count">
+                        <integer>3</integer>
+                    </element>
+                    <element key="maximum-duration">
+                        <integer>250</integer>
+                    </element>
+                </array>
+            </arguments>
+        </extension>
     </extensions>
     <testsuites>
         <testsuite name="unit">
             <directory>test/Unit/</directory>
        </testsuite>
     </testsuites>
 </phpunit>
```

#### Configuring the maximum duration per test case

You can configure the maximum duration for a single test case with a `@maximumDuration` (or `@slowThreshold`) annotation in the DocBlock.

The following example configures the maximum durations for single test cases to 5.000 and 4.000 ms:

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
PHPUnit 10.4.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.0
Configuration: test/EndToEnd/Default/phpunit.xml
Random Seed:   1676103726

.............                                                                                                                                                                                                                                                                                                   13 / 13 (100%)

Detected 11 tests that took longer than expected.

 1. 1.604 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#9
 2. 1.505 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#8
 3. 1.403 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#7
 4. 1.303 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#6
 5. 1.205 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#5
 6. 1.103 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#4
 7. 1.005 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
 8. 0.905 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2
 9. 0.805 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1
10. 0.705 (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#0

There is 1 additional slow test that is not listed here.

Time: 00:12.601, Memory: 8.00 MB

OK (13 tests, 13 assertions)
```

## Changelog

The maintainers of this package record notable changes to this project in a [changelog](CHANGELOG.md).

## Contributing

The maintainers of this package suggest following the [contribution guide](.github/CONTRIBUTING.md).

## Code of Conduct

The maintainers of this package ask contributors to follow the [code of conduct](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## General Support Policy

The maintainers of this package provide limited support.

You can support the maintenance of this package by [sponsoring @localheinz](https://github.com/sponsors/localheinz) or [requesting an invoice for services related to this package](mailto:am@localheinz.com?subject=ergebnis/phpunit-slow-test-detector:%20Requesting%20invoice%20for%20services).

## PHP Version Support Policy

This package supports PHP versions with [active support](https://www.php.net/supported-versions.php).

The maintainers of this package add support for a PHP version following its initial release and drop support for a PHP version when it has reached its end of active support.

## Security Policy

This package has a [security policy](.github/SECURITY.md).

## License

This package uses the [MIT license](LICENSE.md).

## Credits

This package is inspired by [`johnkary/phpunit-speedtrap`](https://github.com/johnkary/phpunit-speedtrap), originally licensed under MIT by [John Kary](https://github.com/johnkary)

## Social

Follow [@localheinz](https://twitter.com/intent/follow?screen_name=localheinz) and [@ergebnis](https://twitter.com/intent/follow?screen_name=ergebnis) on Twitter.
