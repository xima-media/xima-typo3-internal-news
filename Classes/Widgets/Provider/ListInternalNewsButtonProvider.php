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

namespace Xima\XimaTypo3InternalNews\Widgets\Provider;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\ElementAttributesInterface;

/**
 * ListInternalNewsButtonProvider.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0
 */
class ListInternalNewsButtonProvider implements ButtonProviderInterface, ElementAttributesInterface
{
    public function __construct(private readonly string $title, private readonly string $target = '') {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $params = [
            'id' => 0,
            'table' => 'tx_ximatypo3internalnews_domain_model_news',
            'returnUrl' => (string)$uriBuilder->buildUriFromRoute('dashboard'),
        ];

        return (string)$uriBuilder->buildUriFromRoute('web_list', $params);
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getElementAttributes(): array
    {
        return [];
    }
}
