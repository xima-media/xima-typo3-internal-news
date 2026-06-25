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

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\{ButtonProviderInterface, ElementAttributesInterface};


/**
 * CreateInternalNewsButtonProvider.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

class CreateInternalNewsButtonProvider implements ButtonProviderInterface, ElementAttributesInterface
{
    public function __construct(private readonly string $title, private readonly string $target = '') {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $params = [
            'edit' => ['tx_ximatypo3internalnews_domain_model_news' => [0 => 'new']],
            'returnUrl' => (string) $uriBuilder->buildUriFromRoute('dashboard'),
        ];

        return (string) $uriBuilder->buildUriFromRoute('record_edit', $params);
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getElementAttributes(): array
    {
        return [];
    }
}
