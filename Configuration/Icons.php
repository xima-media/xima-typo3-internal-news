<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "xima_typo3_internal_news".
 *
 * Copyright (C) 2024-2025 Konrad Michalik <hej@konradmichalik.dev>
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

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use Xima\XimaTypo3InternalNews\Configuration;

return [
    'internal-news-news' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . Configuration::EXT_KEY . '/Resources/Public/Icons/news.svg',
    ],
    'internal-news-news-color' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . Configuration::EXT_KEY . '/Resources/Public/Icons/news-color.svg',
    ],
    'internal-news-date' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . Configuration::EXT_KEY . '/Resources/Public/Icons/date.svg',
    ],
];
