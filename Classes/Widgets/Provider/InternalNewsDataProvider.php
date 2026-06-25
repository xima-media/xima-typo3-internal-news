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

namespace Xima\XimaTypo3InternalNews\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;


/**
 * InternalNewsDataProvider.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

class InternalNewsDataProvider implements ListDataProviderInterface
{
    public function __construct(protected NewsRepository $newsRepository) {}

    public function getItems(): array
    {
        return $this->newsRepository->findAllByCurrentUser();
    }
}
