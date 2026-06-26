# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0]

### Added

- Optional recurrence support: `simshaun/recurr` is now a suggested dependency
- Cross-tab notification handling via the TYPO3 backend broadcast channel
  (notifications show once across open tabs and dismiss in sync)
- Unit test suite with code coverage reporting via `phpcov`/`pcov`
- Local multi-version development environment via the
  `ddev-typo3-multi-version-extension` DDEV add-on with importable fixtures

### Changed

- Raised requirements to TYPO3 13.4 LTS / 14 and PHP 8.2–8.5
- Moved the code-quality tooling into `Tests/CGL/`
- Page TSconfig is now loaded from `Configuration/page.tsconfig` instead of
  `ext_tables.php` (required for TYPO3 14)

### Removed

- Support for TYPO3 12 and PHP 8.1

### Fixed

- Breaking-news badge no longer spans the full modal width on TYPO3 14

## Upgrading to 2.0.0

`simshaun/recurr` is no longer a hard dependency. Recurring dates and their
reminders are silently disabled when the package is absent. If your editors
rely on recurring dates, install it explicitly:

```bash
composer require simshaun/recurr
```

The minimum platform is now TYPO3 13.4 and PHP 8.2 — upgrade your environment
before updating the extension.
