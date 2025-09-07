<?php

declare(strict_types=1);

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

namespace Xima\XimaTypo3InternalNews\Backend\ToolbarItems;

use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;
use Xima\XimaTypo3InternalNews\Service\NewsService;
use Xima\XimaTypo3InternalNews\Utilities\ViewFactoryHelper;

class NewsItem implements ToolbarItemInterface
{
    protected array $configuration;
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly NewsService $newsService
    ) {}

    /**
    * Checks whether the user has access to this toolbar item
    *
    * @return bool TRUE if user has access, FALSE if not
    */
    public function checkAccess(): bool
    {
        return true;
    }

    /**
    * Render "item" part of this toolbar
    *
    * @return string Toolbar item HTML
    */
    public function getItem(): string
    {
        $items = $this->newsRepository->findAllByCurrentUser();
        $newItemsCount = count(array_filter($items, fn(News $item) => $this->newsService->isNew($item)));

        return ViewFactoryHelper::renderView(
            'Backend/ToolbarItems/NewsItem.html',
            [
                'count' => $newItemsCount,
            ]
        );
    }

    /**
    * TRUE if this toolbar item has a collapsible drop down
    *
    * @return bool
    */
    public function hasDropDown(): bool
    {
        return true;
    }

    /**
    * Render "drop down" part of this toolbar
    *
    * @return string Drop down HTML
    */
    public function getDropDown(): string
    {
        return ViewFactoryHelper::renderView(
            'Backend/ToolbarItems/NewsItemDropDown.html',
            [
                'data' => $this->newsRepository->findAllByCurrentUser(3),
            ]
        );
    }

    /**
    * Returns an array with additional attributes added to containing <li> tag of the item.
    *
    * Typical usages are additional css classes and data-* attributes, classes may be merged
    * with other classes needed by the framework. Do NOT set an id attribute here.
    *
    * array(
    *     'class' => 'my-class',
    *     'data-foo' => '42',
    * )
    *
    * @return array List item HTML attributes
    */
    public function getAdditionalAttributes(): array
    {
        return [];
    }

    /**
    * Returns an integer between 0 and 100 to determine
    * the position of this item relative to others
    *
    * By default, extensions should return 50 to be sorted between main core
    * items and other items that should be on the very right.
    *
    * @return int 0 .. 100
    */
    public function getIndex(): int
    {
        return 50;
    }
}
