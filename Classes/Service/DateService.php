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

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use Xima\XimaTypo3InternalNews\Configuration;
use Xima\XimaTypo3InternalNews\Domain\Model\{Date, News};


/**
 * DateService.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

class DateService
{
    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration,
    ) {}

    public function getNextDate(News $news): ?array
    {
        $nextDate = null;
        foreach ($news->getDates() as $date) {
            $nextDateArray = $this->getDates($news, $date, true, true);
            if (isset($nextDateArray[0])) {
                $nextDate = $nextDateArray[0];
            }
        }

        return $nextDate;
    }

    public function getNextDates(News $news): array
    {
        $nextDates = [];
        foreach ($news->getDates() as $date) {
            // merge arrays
            $nextDates = array_merge($nextDates, $this->getDates($news, $date, true));
        }
        usort($nextDates, static fn (array $a, array $b) => $a['date'] <=> $b['date']);

        return $nextDates;
    }

    public function getNotifyDatesByNewsList(array $newsList): array
    {
        $notifyDates = [];
        foreach ($newsList as $news) {
            foreach ($news->getDates() as $date) {
                $notifyDates = array_merge($notifyDates, $this->getDates($news, $date, true, true));
            }
        }
        usort($notifyDates, static fn (array $a, array $b) => $a['date'] <=> $b['date']);

        return $notifyDates;
    }

    public function getDates(News $news, Date $date, bool $respectNotify = false, bool $forceNotify = false, bool $onlyNextDate = false): array
    {
        $dates = [];
        switch ($date->getType()) {
            case 'single_date':
                if ($date->getSingleDate() > new DateTime() && (!$forceNotify || $this->checkNotifyIsReached($date->getSingleDate()))) {
                    $dates[] = $this->createDateEntry($news, $date, $date->getSingleDate(), $respectNotify);
                    if ($onlyNextDate) {
                        break;
                    }
                }
                break;
            case 'recurrence':
                $transformer = new ArrayTransformer();
                $rule = new Rule($date->getRecurrence(), $date->getSingleDate(), null, (new DateTimeZone('Europe/Berlin'))->getName());
                foreach ($transformer->transform($rule) as $recurrence) {
                    if ($recurrence->getStart() > new DateTime() && (!$forceNotify || $this->checkNotifyIsReached($recurrence->getStart()))) {
                        $dates[] = $this->createDateEntry($news, $date, $recurrence->getStart(), $respectNotify);
                        if ($onlyNextDate) {
                            break 2;
                        }
                    }
                }
                break;
        }

        return $dates;
    }

    private function createDateEntry(News $news, Date $date, DateTimeInterface $startDate, bool $respectNotify): array
    {
        $newDate = [
            'id' => $date->getUid(),
            'date' => $startDate,
            'title' => $date->getTitle(),
            'type' => $date->getType(),
            'newsId' => $news->getUid(),
        ];
        if ($respectNotify && $date->isNotify() && $this->checkNotifyIsReached($startDate)) {
            $newDate['notify'] = true;
            $newDate['notifyType'] = ('' !== $date->getNotifyType()) ? $date->getNotifyType() : 'info';
            $newDate['notifyMessage'] = (('' !== $date->getNotifyMessage()) ? $date->getNotifyMessage() : $GLOBALS['LANG']->sL('LLL:EXT:xima_typo3_internal_news/Resources/Private/Language/locallang.xlf:internal_news_notify_note')).' ('.$startDate->format('d.m.Y H:i').')';
        }

        return $newDate;
    }

    private function checkNotifyIsReached(DateTimeInterface $date): bool
    {
        $extensionConfiguration = $this->extensionConfiguration->get(Configuration::EXT_KEY);
        $threshold = $extensionConfiguration['notifyTimeThreshold'];

        $thresholdDate = clone $date;

        if (!method_exists($thresholdDate, 'modify')) {
            return false;
        }

        $thresholdDate = $thresholdDate->modify('-'.$threshold.' seconds');
        if ($thresholdDate < new DateTime()) {
            return true;
        }

        return false;
    }
}
