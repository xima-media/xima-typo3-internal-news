<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "xima_typo3_internal_news".
 *
 * Copyright (C) 2025 Konrad Michalik <hej@konradmichalik.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

return [
    'directories' => [
        '.build',
        '.ddev',
        '.git',
        '.github',
        'bin',
        'build',
        'public',
        'resources\\/private\\/frontend',
        'resources\\/private\\/libs\\/build',
        'tailor-version-upload',
        'tests',
        'var',
        'vendor',
    ],
    'files' => [
        'DS_Store',
        'CODE_OF_CONDUCT.md',
        'codeception.yml',
        'codecov.yml',
        'CODEOWNERS',
        'composer.lock',
        'CONTRIBUTING.md',
        'crowdin.yaml',
        'dependency-checker.json',
        'docker-compose.yml',
        'editorconfig',
        'editorconfig-lint.php',
        'gitattributes',
        'gitignore',
        'packaging_exclude.php',
        'php-cs-fixer.php',
        'phpstan.php',
        'phpstan-baseline.neon',
        'phpunit.functional.xml',
        'phpunit.unit.xml',
        'rector.php',
        'renovate.json',
        'typoscript-lint.yml',
    ],
];
