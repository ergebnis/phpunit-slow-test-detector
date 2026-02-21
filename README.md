# phpunit-slow-test-detector

[![Integrate](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Integrate/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Merge](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Merge/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Release](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Release/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Renew](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Renew/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/phpunit-slow-test-detector/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/phpunit-slow-test-detector)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/phpunit-slow-test-detector/v/stable)](https://packagist.org/packages/ergebnis/phpunit-slow-test-detector)
[![Total Downloads](https://poser.pugx.org/ergebnis/phpunit-slow-test-detector/downloads)](https://packagist.org/packages/ergebnis/phpunit-slow-test-detector)
[![Monthly Downloads](http://poser.pugx.org/ergebnis/phpunit-slow-test-detector/d/monthly)](https://packagist.org/packages/ergebnis/phpunit-slow-test-detector)

This project provides a [`composer`](https://getcomposer.org) package and a [Phar archive](https://www.php.net/manual/en/book.phar.php) with an extension for detecting slow tests in [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit).

## Example

After installing, configuring, and bootstrapping the extension, when running your tests with `phpunit/phpunit`, the extension will report slow tests:

```console
PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.0
Configuration: test/EndToEnd/Default/phpunit.xml
Random Seed:   1676103726

.............                                                                                                                                                                                                                                                                                                   13 / 13 (100%)

Detected 11 tests where the duration exceeded the global maximum duration (00:00.500).

 # Duration  Test
-----------------------------------------------------------------------------------------------------------------------------------------------------
 1 00:01.604 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#9
 2 00:01.505 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#8
 3 00:01.403 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#7
 4 00:01.303 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#6
 5 00:01.205 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#5
 6 00:01.103 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#4
 7 00:01.005 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3
 8 00:00.905 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2
 9 00:00.805 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1
10 00:00.705 Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#0

There is 1 additional slow test that is not listed here.

Time: 00:12.601, Memory: 8.00 MB

OK (13 tests, 13 assertions)
```

## Compatibility

The extension is compatible with the following versions of `phpunit/phpunit`:

- [`phpunit/phpunit:^13.0.0`](https://github.com/sebastianbergmann/phpunit/tree/13.0.0)
- [`phpunit/phpunit:^12.0.0`](https://github.com/sebastianbergmann/phpunit/tree/12.0.0)
- [`phpunit/phpunit:^11.0.0`](https://github.com/sebastianbergmann/phpunit/tree/11.0.0)
- [`phpunit/phpunit:^10.0.0`](https://github.com/sebastianbergmann/phpunit/tree/10.0.0)
- [`phpunit/phpunit:^9.0.0`](https://github.com/sebastianbergmann/phpunit/tree/9.0.0)
- [`phpunit/phpunit:^8.5.19`](https://github.com/sebastianbergmann/phpunit/tree/8.5.19)
- [`phpunit/phpunit:^7.5.0`](https://github.com/sebastianbergmann/phpunit/tree/7.5.0)
- [`phpunit/phpunit:^6.5.0`](https://github.com/sebastianbergmann/phpunit/tree/6.5.0)

## Installation

### Installation with `composer`

Run

```sh
composer require --dev ergebnis/phpunit-slow-test-detector
```

to install `ergebnis/phpunit-slow-test-detector` as a `composer` package.

### Installation as Phar

Download `phpunit-slow-test-detector.phar` from the [latest release](https://github.com/ergebnis/phpunit-slow-test-detector/releases/latest).

## Usage

### Bootstrapping the extension

Before the extension can detect slow tests in `phpunit/phpunit`, you need to bootstrap it. The bootstrapping mechanism depends on the version of `phpunit/phpunit` you are using.

### Bootstrapping the extension as a `composer` package

To bootstrap the extension as a `composer` package when using

- `phpunit/phpunit:^13.0.0`
- `phpunit/phpunit:^12.0.0`
- `phpunit/phpunit:^11.0.0`
- `phpunit/phpunit:^10.0.0`

adjust your `phpunit.xml` configuration file and configure the

- [`extensions` element](https://docs.phpunit.de/en/13.0/configuration.html#the-extensions-element) on [`phpunit/phpunit:^13.0.0`](https://docs.phpunit.de/en/13.0/)
- [`extensions` element](https://docs.phpunit.de/en/12.0/configuration.html#the-extensions-element) on [`phpunit/phpunit:^12.0.0`](https://docs.phpunit.de/en/12.0/)
- [`extensions` element](https://docs.phpunit.de/en/11.0/configuration.html#the-extensions-element) on [`phpunit/phpunit:^11.0.0`](https://docs.phpunit.de/en/11.0/)
- [`extensions` element](https://docs.phpunit.de/en/10.5/configuration.html#the-extensions-element) on [`phpunit/phpunit:^10.0.0`](https://docs.phpunit.de/en/10.5/)

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

---

To bootstrap the extension as a `composer` package when using

- `phpunit/phpunit:^9.0.0`
- `phpunit/phpunit:^8.5.19`
- `phpunit/phpunit:^7.5.0`

adjust your `phpunit.xml` configuration file and configure the

- [`extensions` element](https://docs.phpunit.de/en/9.6/configuration.html#the-extensions-element) on [`phpunit/phpunit:^9.0.0`](https://docs.phpunit.de/en/9.6/)
- [`extensions` element](https://docs.phpunit.de/en/8.5/configuration.html#the-extensions-element) on [`phpunit/phpunit:^8.5.19`](https://docs.phpunit.de/en/8.5/)
- [`extensions` element](https://docs.phpunit.de/en/7.5/configuration.html#the-extensions-element) on [`phpunit/phpunit:^7.5.0`](https://docs.phpunit.de/en/7.5/)

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

---

To bootstrap the extension as a `composer` package when using

- `phpunit/phpunit:^6.5.0`

adjust your `phpunit.xml` configuration file and configure the

- [`listeners` element](https://phpunit.de/manual/6.5/en/appendixes.configuration.html#appendixes.configuration.test-listeners) on [`phpunit/phpunit:^6.5.0`](https://phpunit.de/manual/6.5/en/)

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
 >
+    <listeners>
+        <listener class="Ergebnis\PHPUnit\SlowTestDetector\Extension"/>
+    </listeners>
     <testsuites>
         <testsuite name="unit">
             <directory>test/Unit/</directory>
         </testsuite>
     </testsuites>
 </phpunit>
```

### Bootstrapping the extension as a PHAR

To bootstrap the extension as a PHAR when using

- `phpunit/phpunit:^13.0.0`
- `phpunit/phpunit:^12.0.0`
- `phpunit/phpunit:^11.0.0`
- `phpunit/phpunit:^10.0.0`

adjust your `phpunit.xml` configuration file and configure the

- [`extensionsDirectory` attribute](https://docs.phpunit.de/en/13.0/configuration.html#the-extensionsdirectory-attribute) and the [`extensions` element](https://docs.phpunit.de/en/13.0/configuration.html#the-extensions-element) on [`phpunit/phpunit:^13.0.0`](https://docs.phpunit.de/en/13.0/)
- [`extensionsDirectory` attribute](https://docs.phpunit.de/en/12.0/configuration.html#the-extensionsdirectory-attribute) and the [`extensions` element](https://docs.phpunit.de/en/12.0/configuration.html#the-extensions-element) on [`phpunit/phpunit:^12.0.0`](https://docs.phpunit.de/en/12.0/)
- [`extensionsDirectory` attribute](https://docs.phpunit.de/en/11.0/configuration.html#the-extensionsdirectory-attribute) and the [`extensions` element](https://docs.phpunit.de/en/11.0/configuration.html#the-extensions-element) on [`phpunit/phpunit:^11.0.0`](https://docs.phpunit.de/en/11.0/)
- [`extensionsDirectory` attribute](https://docs.phpunit.de/en/10.5/configuration.html#the-extensionsdirectory-attribute) and the [`extensions` element](https://docs.phpunit.de/en/10.5/configuration.html#the-extensions-element) on [`phpunit/phpunit:^10.0.0`](https://docs.phpunit.de/en/10.5/)

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
+    extensionsDirectory="directory/where/you/saved/the/extension/phars"
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

---

To bootstrap the extension as a PHAR when using

- `phpunit/phpunit:^9.0.0`
- `phpunit/phpunit:^8.5.19`
- `phpunit/phpunit:^7.5.0`

adjust your `phpunit.xml` configuration file and configure the

- [`extensionsDirectory` attribute](https://docs.phpunit.de/en/9.6/configuration.html#the-extensionsdirectory-attribute) and the [`extensions` element](https://docs.phpunit.de/en/9.6/configuration.html#the-extensions-element) on [`phpunit/phpunit:^9.0.0`](https://docs.phpunit.de/en/9.5/)
- [`extensionsDirectory` attribute](https://docs.phpunit.de/en/8.5/configuration.html#the-extensionsdirectory-attribute) and the [`extensions` element](https://docs.phpunit.de/en/8.5/configuration.html#the-extensions-element) on [`phpunit/phpunit:^8.5.19`](https://docs.phpunit.de/en/8.5/)
- [`extensionsDirectory` attribute](https://docs.phpunit.de/en/7.5/configuration.html#the-extensionsdirectory-attribute) and the [`extensions` element](https://docs.phpunit.de/en/7.5/configuration.html#the-extensions-element) on [`phpunit/phpunit:^7.5.0`](https://docs.phpunit.de/en/7.5/)

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

You can configure the extension with the following options in your `phpunit.xml` configuration file:

- `maximum-count`, an `int`, the maximum count of slow test that should be reported, defaults to `10`
- `maximum-duration`, an `int`, the maximum duration in milliseconds for a test before the extension considers it as a slow test, defaults to `500`

The configuration mechanism depends on the version of `phpunit/phpunit` you are using.

### Configuring the extension

To configure the extension when using

- `phpunit/phpunit:^13.0.0`
- `phpunit/phpunit:^12.0.0`
- `phpunit/phpunit:^11.0.0`
- `phpunit/phpunit:^10.0.0`

adjust your `phpunit.xml` configuration file and configure one or more

- [`parameter` elements](https://docs.phpunit.de/en/13.0/configuration.html#the-parameter-element) on [`phpunit/phpunit:^13.0.0`](https://docs.phpunit.de/en/13.0/)
- [`parameter` elements](https://docs.phpunit.de/en/12.0/configuration.html#the-parameter-element) on [`phpunit/phpunit:^12.0.0`](https://docs.phpunit.de/en/12.0/)
- [`parameter` elements](https://docs.phpunit.de/en/11.0/configuration.html#the-parameter-element) on [`phpunit/phpunit:^11.0.0`](https://docs.phpunit.de/en/11.0/)
- [`parameter` elements](https://docs.phpunit.de/en/10.5/configuration.html#the-parameter-element) on [`phpunit/phpunit:^10.0.0`](https://docs.phpunit.de/en/10.5/)

The following example configures the maximum count of slow tests to three, and the maximum duration for all tests to 250 milliseconds:

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

---

To configure the extension when using

- `phpunit/phpunit:^9.0.0`
- `phpunit/phpunit:^8.5.19`
- `phpunit/phpunit:^7.5.0`

adjust your `phpunit.xml` configuration file and configure the

- [`arguments` element](https://docs.phpunit.de/en/9.6/configuration.html#the-arguments-element) on [`phpunit/phpunit:^9.0.0`](https://docs.phpunit.de/en/9.6/)
- [`arguments` element](https://docs.phpunit.de/en/8.5/configuration.html#the-arguments-element) on [`phpunit/phpunit:^8.5.19`](https://docs.phpunit.de/en/8.5/)
- [`arguments` element](https://docs.phpunit.de/en/7.5/configuration.html#the-arguments-element) on [`phpunit/phpunit:^7.5.0`](https://docs.phpunit.de/en/7.5/)

The following example configures the maximum count of slow tests to three, and the maximum duration for all tests to 250 milliseconds:

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

---

To configure the extension when using

- `phpunit/phpunit:^6.5.0`

adjust your `phpunit.xml` configuration file and configure the

- [`arguments` element](https://phpunit.de/manual/6.5/en/appendixes.configuration.html#appendixes.configuration.test-listeners) on [`phpunit/phpunit:^6.5.0`](https://phpunit.de/manual/6.5/en/)

The following example configures the maximum count of slow tests to three, and the maximum duration for all tests to 250 milliseconds:

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
 >
     <listeners>
-        <listener class="Ergebnis\PHPUnit\SlowTestDetector\Extension"/>
+        <listener class="Ergebnis\PHPUnit\SlowTestDetector\Extension">
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
+        </listener>
     </listeners>
     <testsuites>
         <testsuite name="unit">
             <directory>test/Unit/</directory>
        </testsuite>
     </testsuites>
 </phpunit>
```

### Configuring the maximum duration per test case

You can configure the maximum duration for a single test case with

- an `Attribute\MaximumDuration` attribute when using
  - `phpunit/phpunit:^13.0.0`
  - `phpunit/phpunit:^12.0.0`
  - `phpunit/phpunit:^11.0.0`
  - `phpunit/phpunit:^10.0.0`
- a `@maximumDuration` annotation in the DocBlock when using
  - `phpunit/phpunit:^9.0.0`
  - `phpunit/phpunit:^8.5.19`
  - `phpunit/phpunit:^7.5.0`
  - `phpunit/phpunit:^6.5.0`
- a `@slowThreshold` annotation in the DocBlock when using
  - `phpunit/phpunit:^9.0.0`
  - `phpunit/phpunit:^8.5.19`
  - `phpunit/phpunit:^7.5.0`
  - `phpunit/phpunit:^6.5.0`

The following example configures the maximum durations for single test cases to 5,000 ms, 4,000 ms, and 3,000 ms:

```php
<?php

declare(strict_types=1);

use PHPUnit\Framework;
use Ergebnis\PHPUnit\SlowTestDetector;

final class ExtraSlowTest extends Framework\TestCase
{
    #[SlowTestDetector\Attribute\MaximumDuration(5000)]
    public function testExtraExtraSlow(): void
    {
        // ...
    }

    /**
     * @maximumDuration 4000
     */
    public function testAlsoQuiteSlow(): void
    {
        // ...
    }

    /**
     * @slowThreshold 3000
     */
    public function testQuiteSlow(): void
    {
        // ...
    }
}
```

> [!NOTE]
>
> Support for the `@slowThreshold` annotation exists only to help you move from [`johnkary/phpunit-speedtrap`](https://github.com/johnkary/phpunit-speedtrap). It will be deprecated and removed in the near future.

### Understanding measured test durations

#### Understanding measured test durations when using the new event system

When using

- `phpunit/phpunit:^13.0.0`
- `phpunit/phpunit:^12.0.0`
- `phpunit/phpunit:^11.0.0`
- `phpunit/phpunit:^10.0.0`

the extension uses the new event system of `phpunit/phpunit`, and measures the duration between the points in time when the [`PHPUnit\Event\Test\PreparationStarted`](https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Event/Events/Test/Lifecycle/PreparationStarted.php#L22-L50) and [`PHPUnit\Event\Test\Finished`](https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Event/Events/Test/Lifecycle/Finished.php#L22-L57) are emitted.

#### Understanding measured test durations when using the hooks event system

When using

- `phpunit/phpunit:^9.0.0`
- `phpunit/phpunit:^8.5.19`
- `phpunit/phpunit:^7.5.0`
- `phpunit/phpunit:^6.5.0`

the extension uses the hooks event system of `phpunit/phpunit`, and measures the duration that is passed to the [`PHPUnit\Runner\AfterTestHook`](https://github.com/sebastianbergmann/phpunit/blob/7.5.0/src/Runner/Hook/AfterTestHook.php#L12-L21). This is the [duration of invoking `PHPUnit\Framework\TestCase::runBare()` and more](https://github.com/sebastianbergmann/phpunit/blob/8.5.19/src/Framework/TestResult.php#L671-L754).

> [!NOTE]
> Because of this behavior, the measured test durations can and will vary depending on the order in which `phpunit/phpunit` executes tests.

## Changelog

The maintainers of this project record notable changes to this project in a [changelog](CHANGELOG.md).

## Contributing

The maintainers of this project suggest following the [contribution guide](.github/CONTRIBUTING.md).

## Code of Conduct

The maintainers of this project ask contributors to follow the [code of conduct](.github/CODE_OF_CONDUCT.md).

## General Support Policy

The maintainers of this project provide limited support.

You can support the maintenance of this project by [sponsoring @ergebnis](https://github.com/sponsors/ergebnis).

## PHP Version Support Policy

This project supports PHP versions with [active and security support](https://www.php.net/supported-versions.php).

The maintainers of this project add support for a PHP version following its initial release and drop support for a PHP version when it has reached the end of security support.

## Security Policy

This project has a [security policy](.github/SECURITY.md).

## License

This project uses the [MIT license](LICENSE.md).

## Credits

This package is inspired by [`johnkary/phpunit-speedtrap`](https://github.com/johnkary/phpunit-speedtrap), originally licensed under MIT by [John Kary](https://github.com/johnkary).

## Social

Follow [@localheinz](https://twitter.com/intent/follow?screen_name=localheinz) and [@ergebnis](https://twitter.com/intent/follow?screen_name=ergebnis) on Twitter.
