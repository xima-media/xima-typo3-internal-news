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

namespace Xima\XimaTypo3InternalNews\Service;

use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Utilities\BackendUserHelper;

/**
 * NewsService.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
class NewsService
{
    public function __construct(
        private readonly DateService $dateService,
        private readonly BackendUserHelper $backendUserHelper,
    ) {}

    public function getNextDate(News $news): ?array
    {
        return $this->dateService->getNextDate($news);
    }

    public function getNextDates(News $news): array
    {
        return $this->dateService->getNextDates($news);
    }

    public function isTopAndNew(News $news): bool
    {
        if (!$news->isTop()) {
            return false;
        }

        return $this->backendUserHelper->checkAndSetModuleDate('internal_news/top', $news->getUid());
    }

    public function isNew(News $news): bool
    {
        return $this->backendUserHelper->checkAndSetModuleDate('internal_news/read', $news->getUid());
    }
}
