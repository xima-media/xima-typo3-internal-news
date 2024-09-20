<?php

declare(strict_types=1);

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
            $nextDate = self::getDates($date, true, true)[0];
        }

        return $nextDate;
    }

    public static function getNextDates(News $news): array
    {
        $nextDates = [];
        foreach ($news->getDates() as $date) {
            // merge arrays
            $nextDates = array_merge($nextDates, self::getDates($date, true));
        }
        usort($nextDates, function ($a, $b) {
            return $a['date'] <=> $b['date'];
        });
        return $nextDates;
    }

    public static function getDates(Date $date, bool $respectNotify = false, bool $onlyNextDate = false): array
    {
        $dates = [];
        switch ($date->getType()) {
            case 'single_date':
                if ($date->getSingleDate() > new \DateTime()) {
                    $newDate = [
                        'date' => $date->getSingleDate(),
                        'title' => $date->getTitle(),
                        'type' => $date->getType(),
                    ];
                    if ($respectNotify && $date->isNotify() && self::checkNotifyIsReached($date->getSingleDate())) {
                        $newDate['notify'] = true;
                        $newDate['notifyType'] = $date->getNotifyType() ?: 'info';
                        $newDate['notifyMessage'] = ($date->getNotifyMessage() ?: $GLOBALS['LANG']->sL('LLL:EXT:xima_typo3_internal_news/Resources/Private/Language/locallang.xlf:internal_news_notify_note')) . ' (' . $date->getSingleDate()->format('d.m.Y H:i') . ')';
                    }
                    $dates[] = $newDate;
                    if ($onlyNextDate) {
                        break;
                    }
                }
                break;
            case 'recurrence':
                $transformer = new ArrayTransformer();
                $rule = new Rule($date->getRecurrence(), $date->getSingleDate(), null, (new \DateTimeZone('Europe/Berlin'))->getName());
                foreach ($transformer->transform($rule) as $recurrence) {
                    if ($recurrence->getStart() > new \DateTime()) {
                        $newDate = [
                            'date' => $recurrence->getStart(),
                            'title' => $date->getTitle(),
                            'type' => $date->getType(),
                        ];
                        if ($respectNotify && $date->isNotify() && self::checkNotifyIsReached($recurrence->getStart())) {
                            $newDate['notify'] = true;
                            $newDate['notifyType'] = $date->getNotifyType() ?: 'info';
                            $newDate['notifyMessage'] = ($date->getNotifyMessage() ?: $GLOBALS['LANG']->sL('LLL:EXT:xima_typo3_internal_news/Resources/Private/Language/locallang.xlf:internal_news_notify_note')) . ' (' . $recurrence->getStart()->format('d.m.Y H:i') . ')';
                        }
                        $dates[] = $newDate;
                        if ($onlyNextDate) {
                            break 2;
                        }
                    }
                }
                break;
        }
        return $dates;
    }

    private static function checkNotifyIsReached(\DateTimeInterface $date): bool
    {
        $extensionConfiguration = (GeneralUtility::makeInstance(ExtensionConfiguration::class))->get(Configuration::EXT_KEY);
        $threshold = $extensionConfiguration['notifyTimeThreshold'];

        $thresholdDate = clone $date;

        if (!method_exists($date, 'modify')) {
            return false;
        }

        $thresholdDate= $thresholdDate->modify('-' . $threshold . ' seconds');
        if ($thresholdDate < new \DateTime()) {
            return true;
        }
        return false;
    }
}
