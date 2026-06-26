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

return [
    'internal_news_notifies' => [
        'path' => '/internal-news/notifies',
        'target' => Xima\XimaTypo3InternalNews\Controller\DateController::class.'::notifiesAction',
    ],
    'internal_news_detail' => [
        'path' => '/internal-news/detail',
        'target' => Xima\XimaTypo3InternalNews\Controller\DateController::class.'::newsAction',
    ],
    'internal_news_list' => [
        'path' => '/internal-news/list',
        'target' => Xima\XimaTypo3InternalNews\Controller\DateController::class.'::listAction',
    ],
];
