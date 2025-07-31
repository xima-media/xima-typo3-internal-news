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

namespace Xima\XimaTypo3InternalNews\Service;

use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Utilities\BackendUserHelper;

class NewsService
{
    public function __construct(
        private readonly DateService $dateService,
        private readonly BackendUserHelper $backendUserHelper
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
