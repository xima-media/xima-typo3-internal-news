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

namespace Xima\XimaTypo3InternalNews\Utilities;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XimaTypo3InternalNews\Domain\Model\{Date, News};
use Xima\XimaTypo3InternalNews\Domain\Repository\DateRepository;
use Xima\XimaTypo3InternalNews\Service\DateService;

use function array_key_exists;

/**
 * UserFunc.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
class UserFunc
{
    public function dateLabel(array &$parameters): void
    {
        $record = GeneralUtility::makeInstance(DateRepository::class)->findByUid($parameters['row']['uid']);
        if (!$record instanceof Date) {
            return;
        }

        $dateService = GeneralUtility::makeInstance(DateService::class);
        $dates = $dateService->getDates(GeneralUtility::makeInstance(News::class), $record);
        if ([] === $dates) {
            return;
        }

        $title = (array_key_exists('title', $parameters['row']) ? $parameters['row']['title'] : array_key_exists('title', $parameters)) ? $parameters['title'] : '';
        $label = ('single_date' === $dates[0]['type'] ? '📅 ' : '🗓️ ').$title;

        $rawDates = [];

        foreach ($dates as $date) {
            $rawDates[] = $date['date']->format('d.m.Y H:i');
        }
        $label .= ' – '.implode(', ', $rawDates);

        $parameters['title'] = $label;
    }
}
