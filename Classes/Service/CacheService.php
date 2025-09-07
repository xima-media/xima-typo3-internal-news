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

namespace Xima\XimaTypo3InternalNews\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use Xima\XimaTypo3InternalNews\Configuration;


/**
 * CacheService.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0
 */

class CacheService
{
    public function __construct(
        private FrontendInterface $cache
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
            return Configuration::EXT_KEY . '--all';
        }
        sort($userGroups);
        return Configuration::EXT_KEY . '--' . implode('_', $userGroups);
    }

    private function collectCacheTags(array $data): array
    {
        $tags = ['tx_ximatypo3internalnews_domain_model_news'];
        foreach ($data as $item) {
            $tags[] = 'tx_ximatypo3internalnews_domain_model_news_' . $item->getUid();
        }
        return $tags;
    }
}
