<?php

declare(strict_types=1);

/*
 * This file is part of the "xima_typo3_internal_news" TYPO3 CMS extension.
 *
 * (c) 2025-2026 Konrad Michalik <hej@konradmichalik.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use Xima\XimaTypo3InternalNews\Configuration;

return [
    'internal-news-news' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:'.Configuration::EXT_KEY.'/Resources/Public/Icons/news.svg',
    ],
    'internal-news-news-color' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:'.Configuration::EXT_KEY.'/Resources/Public/Icons/news-color.svg',
    ],
    'internal-news-date' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:'.Configuration::EXT_KEY.'/Resources/Public/Icons/date.svg',
    ],
];
