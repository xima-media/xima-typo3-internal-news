<?php

declare(strict_types=1);

namespace Xima\XimaTypo3InternalNews\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;

class InternalNewsDataProvider implements ListDataProviderInterface
{
    public function __construct(protected NewsRepository $newsRepository)
    {
    }

    public function getItems(): array
    {
        return $this->newsRepository->findAllByCurrentUser();
    }
}
