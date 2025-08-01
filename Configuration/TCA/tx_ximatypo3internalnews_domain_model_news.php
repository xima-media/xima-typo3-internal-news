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

use TYPO3\CMS\Core\Resource\FileType;
use Xima\XimaTypo3InternalNews\Configuration;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'adminOnly' => true,
        'rootLevel' => 1,
        'delete' => 'deleted',
        'groupName' => 'internal_news',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'default_sortby' => 'ORDER BY title',
        'sortby' => 'sorting',
        'typeicon_classes' => [
            'default' => 'internal-news-news-color',
        ],
        'searchFields' => 'title, description',
    ],
    'types' => [
        '0' => [
            'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;newsPalette,
        --div--;LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.files,
            media,
        --div--;LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.categories,
            categories,
        --div--;LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.dates,
            dates,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
            --palette--;;accessPalette,
    ',
        ],
    ],
    'palettes' => [
        'newsPalette' => [
            'showitem' => 'title, top, --linebreak--, description',
        ],
        'accessPalette' => [
            'showitem' => 'hidden, starttime, endtime, --linebreak--, be_group',
        ],

    ],
    'columns' => [
        'title' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.title',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'top' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.top',
            'description' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.top.description',
            'exclude' => false,
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'description' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 8,
                'cols' => 40,
                'max' => 2000,
                'eval' => 'trim',
            ],
        ],
        'media' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.media',
            'exclude' => false,
            'config' => [
                'type' => 'file',
                'allowed' => 'common-media-types',
                'overrideChildTca' => [
                    'types' => [
                        FileType::IMAGE->value => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                        ],
                    ],
                ],
            ],
        ],
        'dates' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.dates',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_ximatypo3internalnews_domain_model_date',
                'foreign_field' => 'news',
                'minitems' => 0,
                'maxitems' => 9999,
            ],
        ],
        'categories'       => [
            'label'  => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.categories',
            'config' => [
                'type' => 'category',
            ],
        ],
        'be_group' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.be_group',
            'description' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_news.be_group.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_groups',
                'size' => 3,
                'autoSizeMax' => 10,
            ],
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
    ],
];
