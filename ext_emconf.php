<?php

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

$EM_CONF[$_EXTKEY] = [
    'title' => 'Internal news',
    'description' => 'This extension provide internal news for backend users.',
    'category' => 'module',
    'author' => 'Konrad Michalik',
    'author_email' => 'konrad.michalik@xima.de',
    'author_company' => 'XIMA MEDIA GmbH',
    'state' => 'stable',
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.3.99',
            'typo3' => '12.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
