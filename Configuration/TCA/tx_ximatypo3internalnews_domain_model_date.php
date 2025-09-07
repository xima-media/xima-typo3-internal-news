<?php

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

use Xima\XimaTypo3InternalNews\Configuration;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date',
        'label' => 'title',
        'label_userFunc' => \Xima\XimaTypo3InternalNews\Utilities\UserFunc::class . '->dateLabel',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'hideTable' => true,
        'rootLevel' => 1,
        'default_sortby' => 'ORDER BY tstamp',
        'typeicon_classes' => [
            'default' => 'internal-news-date',
        ],
        'searchFields' => 'title',
    ],
    'types' => [
        '0' => [
            'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;datePalette,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
            hidden,
    ',
        ],
    ],
    'palettes' => [
        'datePalette' => [
            'showitem' => 'title, --linebreak--, type, single_date, recurrence, --linebreak--, notify, notify_type, notify_message',
        ],
    ],
    'columns' => [
        'title' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.title',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'type' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.type',
            'description' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.type.description',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'Single date',
                        'value' => 'single_date',
                    ],
                    [
                        'label' => 'Recurring',
                        'value' => 'recurrence',
                    ],
                ],
                'required' => true,
            ],
        ],
        'single_date' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.single_date',
            'description' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.single_date.description',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'required' => false,
                'size' => 20,
                'default' => 0,
            ],
        ],
        'recurrence' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.recurrence',
            'description' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.recurrence.description',
            'exclude' => false,
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => false,
            ],
            'displayCond' => 'FIELD:type:=:recurrence',
        ],
        'notify' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.notify',
            'description' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.notify.description',
            'exclude' => false,
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'notify_type' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.notify_type',
            'description' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.notify_type.description',
            'displayCond' => 'FIELD:notify:=:1',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'Notice',
                        'value' => 'notice',
                    ],
                    [
                        'label' => 'Info',
                        'value' => 'info',
                    ],
                    [
                        'label' => 'Success',
                        'value' => 'success',
                    ],
                    [
                        'label' => 'Warning',
                        'value' => 'warning',
                    ],
                    [
                        'label' => 'Error',
                        'value' => 'error',
                    ],
                ],
                'required' => false,
            ],
        ],
        'notify_message' => [
            'label' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.notify_message',
            'description' => 'LLL:EXT:' . Configuration::EXT_KEY . '/Resources/Private/Language/locallang_db.xlf:tx_ximatypo3internalnews_domain_model_date.notify_message.description',
            'displayCond' => 'FIELD:notify:=:1',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'max' => 255,
                'eval' => 'trim',
                'required' => false,
            ],
        ],
        'news' => [
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
    ],
];
