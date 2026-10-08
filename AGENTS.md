# AGENTS.md

Guidance for coding agents working in this repository.

## Project overview

`xima/xima-typo3-internal-news` is a TYPO3 extension (`xima_typo3_internal_news`) that provides an internal news system for backend users: news records with notification dates (including recurring events), a toolbar item, a dashboard widget and modal dialogs.

- PHP: `~8.2 || ~8.3 || ~8.4 || ~8.5`
- TYPO3: `^13.4 || ^14.0`
- Namespace: `Xima\XimaTypo3InternalNews\` maps to `Classes/`

## Structure

- `Classes/Backend/ToolbarItems/`: backend toolbar dropdown for quick news access
- `Classes/Controller/`: `DateController` with AJAX endpoints for date operations
- `Classes/Domain/`: `Model/` and `Repository/` for news and dates
- `Classes/Hooks/`: `DataHandler` hook that clears caches
- `Classes/Service/`: `CacheService`, `DateService`, `NewsService`
- `Classes/Utilities/`: `BackendUserHelper`, `UserFunc`, `ViewFactoryHelper`
- `Classes/ViewHelpers/`, `Classes/Widgets/` (`InternalNewsWidget`), `Classes/Provider/`
- `Configuration/`: TCA, backend routes, icons, JavaScript modules, services
- `Resources/`: Fluid templates, XLIFF language files (EN and DE), CSS, JavaScript modules
- `Tests/Unit/`: PHPUnit tests mirroring `Classes/`
- `Tests/CGL/`: separate Composer project with code style and static analysis tools
- `Documentation/`: TYPO3 documentation source
- `.ddev/`: DDEV setup with TYPO3 13 and 14 instances

## Development commands

```bash
ddev start
ddev composer install
ddev install all               # or 13, 14
ddev launch                    # open the development site
ddev 13 typo3 cache:flush      # TYPO3 commands per version
ddev all typo3 database:updateschema
```

## Testing

There is a unit suite only, no functional or E2E tests.

```bash
composer test                  # phpunit -c phpunit.xml, no coverage
composer test:coverage         # unit suite with coverage, merged with phpcov into .Build/coverage
vendor/bin/phpunit --filter <name>
```

CI (`.github/workflows/tests.yml`, reusable workflow) runs PHP 8.2 to 8.5 against TYPO3 13.4 and 14.3 with highest and lowest dependencies.

## Code style and static analysis

Tools live in `Tests/CGL/` and run through `composer cgl` (or `ddev cgl`). Root shortcuts exist for `composer lint`, `composer fix`, `composer sca` and `composer migration`.

```bash
composer lint                  # composer, editorconfig, language, php, typoscript
composer fix                   # composer, editorconfig, php
composer sca                   # PHPStan level 5
composer migration             # Rector
composer cgl analyze           # composer-dependency-analyser
```

- Individual targets: `lint:composer`, `lint:editorconfig`, `lint:language`, `lint:php`, `lint:typoscript`, `fix:composer`, `fix:editorconfig`, `fix:php`, `sca:php`
- Config files: `Tests/CGL/phpstan.neon`, `Tests/CGL/.php-cs-fixer.php`, `Tests/CGL/rector.php`
- CI runs the CGL workflow (`.github/workflows/cgl.yml`) on pushes to `main` and on pull requests

## Git workflow

- Commit format: `<type>: <description>` with type one of `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`, `ci`
- Do not add co-author trailers
- Run lint, static analysis and tests before opening a pull request
