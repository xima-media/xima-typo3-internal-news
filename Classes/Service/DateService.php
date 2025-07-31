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

use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XimaTypo3InternalNews\Configuration;
use Xima\XimaTypo3InternalNews\Domain\Model\Date;
use Xima\XimaTypo3InternalNews\Domain\Model\News;

class DateService
{
    public static function getNextDate(News $news): ?array
    {
        $nextDate = null;
        foreach ($news->getDates() as $date) {
            $nextDateArray = self::getDates($news, $date, true, true);
            if (isset($nextDateArray[0])) {
                $nextDate = $nextDateArray[0];
            }
        }

        return $nextDate;
    }

    public static function getNextDates(News $news): array
    {
        $nextDates = [];
        foreach ($news->getDates() as $date) {
            // merge arrays
            $nextDates = array_merge($nextDates, self::getDates($news, $date, true));
        }
        usort($nextDates, function (array $a, array $b) {
            return $a['date'] <=> $b['date'];
        });
        return $nextDates;
    }

    public static function getNotifyDatesByNewsList(array $newsList): array
    {
        $notifyDates = [];
        foreach ($newsList as $news) {
            foreach ($news->getDates() as $date) {
                $notifyDates = array_merge($notifyDates, self::getDates($news, $date, true, true));
            }
        }
        usort($notifyDates, function (array $a, array $b) {
            return $a['date'] <=> $b['date'];
        });
        return $notifyDates;
    }

    public static function getDates(News $news, Date $date, bool $respectNotify = false, bool $forceNotify = false, bool $onlyNextDate = false): array
    {
        $dates = [];
        switch ($date->getType()) {
            case 'single_date':
                if ($date->getSingleDate() > new \DateTime() && (!$forceNotify || self::checkNotifyIsReached($date->getSingleDate()))) {
                    $dates[] = self::createDateEntry($news, $date, $date->getSingleDate(), $respectNotify);
                    if ($onlyNextDate) {
                        break;
                    }
                }
                break;
            case 'recurrence':
                $transformer = new ArrayTransformer();
                $rule = new Rule($date->getRecurrence(), $date->getSingleDate(), null, (new \DateTimeZone('Europe/Berlin'))->getName());
                foreach ($transformer->transform($rule) as $recurrence) {
                    if ($recurrence->getStart() > new \DateTime() && (!$forceNotify || self::checkNotifyIsReached($recurrence->getStart()))) {
                        $dates[] = self::createDateEntry($news, $date, $recurrence->getStart(), $respectNotify);
                        if ($onlyNextDate) {
                            break 2;
                        }
                    }
                }
                break;
        }
        return $dates;
    }

    private static function createDateEntry(News $news, Date $date, \DateTimeInterface $startDate, bool $respectNotify): array
    {
        $newDate = [
            'id' => $date->getUid(),
            'date' => $startDate,
            'title' => $date->getTitle(),
            'type' => $date->getType(),
            'newsId' => $news->getUid(),
        ];
        if ($respectNotify && $date->isNotify() && self::checkNotifyIsReached($startDate)) {
            $newDate['notify'] = true;
            $newDate['notifyType'] = ($date->getNotifyType() !== '') ? $date->getNotifyType() : 'info';
            $newDate['notifyMessage'] = (($date->getNotifyMessage() !== '') ? $date->getNotifyMessage() : $GLOBALS['LANG']->sL('LLL:EXT:xima_typo3_internal_news/Resources/Private/Language/locallang.xlf:internal_news_notify_note')) . ' (' . $startDate->format('d.m.Y H:i') . ')';        }
        return $newDate;
    }

    private static function checkNotifyIsReached(\DateTimeInterface $date): bool
    {
        $extensionConfiguration = (GeneralUtility::makeInstance(ExtensionConfiguration::class))->get(Configuration::EXT_KEY);
        $threshold = $extensionConfiguration['notifyTimeThreshold'];

        $thresholdDate = clone $date;

        if (!method_exists($thresholdDate, 'modify')) {
            return false;
        }

        $thresholdDate = $thresholdDate->modify('-' . $threshold . ' seconds');
        if ($thresholdDate < new \DateTime()) {
            return true;
        }
        return false;
    }
}
