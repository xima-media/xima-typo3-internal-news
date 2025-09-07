<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "xima_typo3_internal_news".
 *
 * Copyright (C) 2024-2025 Konrad Michalik <hej@konradmichalik.dev>
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

namespace Xima\XimaTypo3InternalNews\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Xima\XimaTypo3InternalNews\Configuration;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;
use Xima\XimaTypo3InternalNews\Service\DateService;

#[AsController]

/**
 * DateController.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0
 */
final class DateController extends ActionController
{
    protected array $configuration;
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly ExtensionConfiguration $extensionConfiguration
    ) {
        $this->configuration = $this->extensionConfiguration->get(Configuration::EXT_KEY);
    }

    public function notifiesAction(): ResponseInterface
    {
        $newsList = $this->newsRepository->findAllByCurrentUser()->toArray();
        $notifies = DateService::getNotifyDatesByNewsList($newsList);

        return new JsonResponse([
            'notifies' => $notifies,
            'enableNotifySessionHide' => $this->configuration['enableNotifySessionHide'],
        ]);
    }

    public function newsAction(): ResponseInterface
    {
        $newsUid = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['newsId'];
        $news = $this->newsRepository->findByUid((int)$newsUid);

        $view = GeneralUtility::makeInstance(StandaloneView::class);

        $view->setTemplateRootPaths(['EXT:' . Configuration::EXT_KEY . '/Resources/Private/Templates']);
        $view->setPartialRootPaths(['EXT:' . Configuration::EXT_KEY . '/Resources/Private/Partials']);
        $view->setLayoutRootPaths(['EXT:' . Configuration::EXT_KEY . '/Resources/Private/Layouts']);

        $view->setTemplate('News');
        $view->assignMultiple([
            'record' => $news,
            'dateListCount' => (array_key_exists('dateListCount', $this->configuration) ? (int)$this->configuration['dateListCount'] : 20),
        ]);
        return new JsonResponse(['result' => $view->render()]);
    }
}
