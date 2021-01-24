# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`7afa59c...main`][7afa59c...main].

### Added

* Added `SlowTest` ([#6]), by [@localheinz]
* Added `SlowTestCollector` ([#8]), by [@localheinz]
* Added `Subscriber\TestPreparedSubscriber` ([#12]), by [@localheinz]
* Added `Subscriber\TestPassedSubscriber` ([#13]), by [@localheinz]
* Added `Formatter\ToMillisecondsDurationFormatter` ([#17]), by [@localheinz]
* Added `Comparator\DurationComparator` ([#18]), by [@localheinz]
* Added `SlowTestReporter` ([#19]), by [@localheinz]
* Extracted `TimeKeeper` ([#22]), by [@localheinz]

### Changed

* Renamed `SlowTestReporter` to `Reporter\Reporter` ([#20]), by [@localheinz]
* Renamed `Reporter\Reporter` to `Reporter\DefaultReporter` and extracted `Reporter\Reporter` interface ([#21]), by [@localheinz]

[7afa59c...main]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/7afa59c...main

[#6]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/6
[#8]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/8
[#12]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/12
[#13]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/13
[#17]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/17
[#18]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/18
[#19]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/19
[#20]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/20
[#21]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/21
[#22]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/22

[@localheinz]: https://github.com/localheinz
