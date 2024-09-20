<?php

// TypoScript für das Backend laden
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
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
