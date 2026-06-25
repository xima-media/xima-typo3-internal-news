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

return [
    'dependencies' => ['core', 'backend'],
    'imports' => [
        '@xima/ximatypo3internalnews/' => 'EXT:xima_typo3_internal_news/Resources/Public/JavaScript/',
    ],
];
