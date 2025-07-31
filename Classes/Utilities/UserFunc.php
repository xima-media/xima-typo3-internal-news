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

namespace Xima\XimaTypo3InternalNews\Utilities;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XimaTypo3InternalNews\Domain\Model\Date;
use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Domain\Repository\DateRepository;
use Xima\XimaTypo3InternalNews\Service\DateService;

class UserFunc
{
    public function dateLabel(array &$parameters): void
    {
        $record = GeneralUtility::makeInstance(DateRepository::class)->findByUid($parameters['row']['uid']);
        if (!$record instanceof Date) {
            return;
        }

        $dates = DateService::getDates(GeneralUtility::makeInstance(News::class), $record);
        if ($dates === []) {
            return;
        }

        $title = (array_key_exists('title', $parameters['row']) ? $parameters['row']['title'] : array_key_exists('title', $parameters)) ? $parameters['title'] : '';
        $label = ($dates[0]['type'] === 'single_date' ? '📅 ' : '🗓️ ') . $title;

        $rawDates = [];

        foreach ($dates as $date) {
            $rawDates[] = $date['date']->format('d.m.Y H:i');
        }
        $label .= ' – ' . implode(', ', $rawDates);

        $parameters['title'] = $label;
    }
}
