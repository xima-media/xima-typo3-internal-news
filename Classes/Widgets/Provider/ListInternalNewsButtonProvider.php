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
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\{ButtonProviderInterface, ElementAttributesInterface};

/**
 * ListInternalNewsButtonProvider.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
class ListInternalNewsButtonProvider implements ButtonProviderInterface, ElementAttributesInterface
{
    public function __construct(private readonly string $title, private readonly string $target = '') {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

        // TYPO3 v14 renamed the record list module to "records"; "web_list" only remains
        // as an alias. UriBuilder generates the CSRF token for the passed route name, while
        // RouteDispatcher validates it against the resolved identifier — passing the alias
        // causes a token mismatch and bounces the user back to the start module (dashboard).
        $listModuleIdentifier = (new Typo3Version())->getMajorVersion() >= 14 ? 'records' : 'web_list';

        $params = [
            'id' => 0,
            'table' => 'tx_ximatypo3internalnews_domain_model_news',
            'returnUrl' => (string) $uriBuilder->buildUriFromRoute('dashboard'),
        ];

        return (string) $uriBuilder->buildUriFromRoute($listModuleIdentifier, $params);
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
