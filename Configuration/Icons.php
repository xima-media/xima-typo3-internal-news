<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use Xima\XimaTypo3InternalNews\Configuration;

return [
    // flag
    'internal-news-news' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . Configuration::EXT_KEY . '/Resources/Public/Icons/news.svg',
    ],
    'internal-news-date' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . Configuration::EXT_KEY . '/Resources/Public/Icons/date.svg',
    ],
];
