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

namespace Xima\XimaTypo3InternalNews\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Service\NewsService;


/**
 * NextDatesViewHelper.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

class NextDatesViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('news', News::class, 'The news object', true);
    }

    public function render(): array
    {
        $news = $this->arguments['news'];
        if (!$news instanceof News) {
            return [];
        }

        $newsService = GeneralUtility::makeInstance(NewsService::class);

        return $newsService->getNextDates($news);
    }
}
