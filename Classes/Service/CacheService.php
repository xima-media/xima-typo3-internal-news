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

namespace Xima\XimaTypo3InternalNews\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use Xima\XimaTypo3InternalNews\Configuration;

/**
 * CacheService.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
class CacheService
{
    public function __construct(
        private readonly FrontendInterface $cache,
    ) {}

    public function has(string $identifier): bool
    {
        return $this->cache->has($identifier);
    }

    public function get(string $identifier): mixed
    {
        return $this->cache->get($identifier);
    }

    public function set(string $identifier, mixed $data): void
    {
        $this->cache->set($identifier, $data, $this->collectCacheTags($data));
    }

    public function generateCacheIdentifier(array $userGroups = []): string
    {
        if ($GLOBALS['BE_USER']->isAdmin()) {
            return Configuration::EXT_KEY.'--all';
        }
        sort($userGroups);

        return Configuration::EXT_KEY.'--'.implode('_', $userGroups);
    }

    private function collectCacheTags(array $data): array
    {
        $tags = ['tx_ximatypo3internalnews_domain_model_news'];
        foreach ($data as $item) {
            $tags[] = 'tx_ximatypo3internalnews_domain_model_news_'.$item->getUid();
        }

        return $tags;
    }
}
