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

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
    mod {
        web_layout {
            backendLayouts {
                default {
                    jsFile = EXT:xima_typo3_internal_news/Resources/Public/JavaScript/backend.js
                }
            }
        }
    }
');
