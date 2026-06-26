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

namespace Xima\XimaTypo3InternalNews\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XimaTypo3InternalNews\Configuration;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;
use Xima\XimaTypo3InternalNews\Service\DateService;
use Xima\XimaTypo3InternalNews\Utilities\ViewFactoryHelper;

use function array_key_exists;

/**
 * DateController.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
#[AsController]
final class DateController extends ActionController
{
    protected array $configuration;

    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly ExtensionConfiguration $extensionConfiguration,
        private readonly DateService $dateService,
    ) {
        $this->configuration = $this->extensionConfiguration->get(Configuration::EXT_KEY);
    }

    public function notifiesAction(): ResponseInterface
    {
        $newsList = $this->newsRepository->findAllByCurrentUser();
        $notifies = $this->dateService->getNotifyDatesByNewsList($newsList);

        return new JsonResponse([
            'notifies' => $notifies,
            'enableNotifySessionHide' => $this->configuration['enableNotifySessionHide'],
        ]);
    }

    public function listAction(): JsonResponse
    {
        return new JsonResponse(
            [
                'result' => ViewFactoryHelper::renderView(
                    'Default/List.html',
                    [
                        'records' => $this->newsRepository->findAllByCurrentUser() ?? [],
                    ],
                ),
            ],
        );
    }

    public function newsAction(): ResponseInterface
    {
        $queryParams = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
        $newsId = $queryParams['newsId'] ?? null;

        if (null === $newsId || !is_numeric($newsId)) {
            return new JsonResponse(['error' => 'Invalid or missing newsId parameter'], 400);
        }

        $newsUid = (int) $newsId;
        if ($newsUid <= 0) {
            return new JsonResponse(['error' => 'newsId must be a positive integer'], 400);
        }

        $news = $this->newsRepository->findByUid($newsUid);

        if (null === $news) {
            return new JsonResponse(['error' => 'News item not found'], 404);
        }

        return new JsonResponse(
            [
                'result' => ViewFactoryHelper::renderView(
                    'Default/News.html',
                    [
                        'record' => $news,
                        'dateListCount' => (array_key_exists('dateListCount', $this->configuration) ? (int) $this->configuration['dateListCount'] : 20),
                    ],
                ),
            ],
        );
    }
}
