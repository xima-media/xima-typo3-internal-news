<?php

declare(strict_types=1);

namespace Xima\XimaTypo3InternalNews\Utilities;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Domain\Repository\DateRepository;
use Xima\XimaTypo3InternalNews\Service\DateService;

class UserFunc
{
    public function dateLabel(array &$parameters): void
    {
        $record = GeneralUtility::makeInstance(DateRepository::class)->findByUid($parameters['row']['uid']);
        if ($record === null) {
            return;
        }

        $dates = DateService::getDates(GeneralUtility::makeInstance(News::class), $record);
        if (empty($dates)) {
            return;
        }
        $title = (array_key_exists('title', $parameters['row']) ? $parameters['row']['title'] : array_key_exists('title', $parameters)) ? $parameters['title'] : '';
        $label = ($dates[0]['type'] === 'single_date' ? '📅 ': '🗓️ ') . $title;

        $rawDates = [];

        foreach ($dates as $date) {
            $rawDates[] = $date['date']->format('d.m.Y H:i');
        }
        $label .= ' – ' . implode(', ', $rawDates);

        $parameters['title'] = $label;
    }
}
