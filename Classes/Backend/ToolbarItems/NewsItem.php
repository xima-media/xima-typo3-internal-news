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

namespace Xima\XimaTypo3InternalNews\Backend\ToolbarItems;

use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;
use Xima\XimaTypo3InternalNews\Service\NewsService;
use Xima\XimaTypo3InternalNews\Utilities\ViewFactoryHelper;

use function count;


/**
 * NewsItem.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

class NewsItem implements ToolbarItemInterface
{
    protected array $configuration;

    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly NewsService $newsService,
    ) {}

    /**
     * Checks whether the user has access to this toolbar item.
     *
     * @return bool TRUE if user has access, FALSE if not
     */
    public function checkAccess(): bool
    {
        return true;
    }

    /**
     * Render "item" part of this toolbar.
     *
     * @return string Toolbar item HTML
     */
    public function getItem(): string
    {
        $items = $this->newsRepository->findAllByCurrentUser();
        $newItemsCount = count(array_filter($items, $this->newsService->isNew(...)));

        return ViewFactoryHelper::renderView(
            'Backend/ToolbarItems/NewsItem.html',
            [
                'count' => $newItemsCount,
            ],
        );
    }

    /**
     * TRUE if this toolbar item has a collapsible drop down.
     */
    public function hasDropDown(): bool
    {
        return true;
    }

    /**
     * Render "drop down" part of this toolbar.
     *
     * @return string Drop down HTML
     */
    public function getDropDown(): string
    {
        return ViewFactoryHelper::renderView(
            'Backend/ToolbarItems/NewsItemDropDown.html',
            [
                'data' => $this->newsRepository->findAllByCurrentUser(3),
            ],
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
     * the position of this item relative to others.
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
