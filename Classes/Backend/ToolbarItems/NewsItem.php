<?php

namespace Xima\XimaTypo3InternalNews\Backend\ToolbarItems;

use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Xima\XimaTypo3InternalNews\Configuration;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;

class NewsItem implements ToolbarItemInterface
{
    public function __construct(private readonly NewsRepository $newsRepository)
    {
    }

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
        $items = $this->newsRepository->findAllByCurrentUser()->toArray();
        $newItemsCount = count(array_filter($items, fn ($item) => $item->isNew()));
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:' . Configuration::EXT_KEY
            . '/Resources/Private/Templates/Backend/ToolbarItems/NewsItem.html'));

        return $view->assignMultiple([
            'count' => $newItemsCount,
        ])->render();
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
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:' . Configuration::EXT_KEY
            . '/Resources/Private/Templates/Backend/ToolbarItems/NewsItemDropDown.html'));
        $view->setPartialRootPaths([
            GeneralUtility::getFileAbsFileName('EXT:' . Configuration::EXT_KEY . '/Resources/Private/Partials/'),
        ]);
        return $view->assignMultiple([
            'data' => $this->newsRepository->findAllByCurrentUser(),
        ])->render();
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
