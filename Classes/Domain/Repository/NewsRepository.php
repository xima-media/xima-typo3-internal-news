<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "xima_typo3_internal_news".
 *
 * Copyright (C) 2025 Konrad Michalik <hej@konradmichalik.dev>
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

namespace Xima\XimaTypo3InternalNews\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Xima\XimaTypo3InternalNews\Service\CacheService;

class NewsRepository extends Repository
{
    public function __construct(
        private CacheService $cache
    ) {
        parent::__construct();
    }

    protected $defaultOrderings = [
        'tstamp' => QueryInterface::ORDER_DESCENDING,
    ];

    public function findAllByCurrentUser(int|null $limit = null): array|null
    {
        // Validate backend user exists and is authenticated
        if (!isset($GLOBALS['BE_USER']) || !is_object($GLOBALS['BE_USER'])) {
            return [];
        }

        $backendUser = $GLOBALS['BE_USER'];

        // Validate user groups property exists and is an array
        if (!property_exists($backendUser, 'userGroups') || !is_array($backendUser->userGroups)) {
            return [];
        }

        $userGroups = array_keys($backendUser->userGroups);
        $cacheIdentifier = $this->cache->generateCacheIdentifier($userGroups);
        if ($this->cache->has($cacheIdentifier)) {
            return $this->cache->get($cacheIdentifier);
        }

        // Check if user has admin privileges
        if (method_exists($backendUser, 'isAdmin') && $backendUser->isAdmin()) {
            $result = $this->findAll()->toArray();
            $this->cache->set($cacheIdentifier, $result);
            return $result;
        }

        $query = $this->createQuery();

        if ($userGroups !== []) {
            $query->matching(
                $query->logicalOr(
                    $query->equals('be_group', 0),
                    $query->in('be_group', $userGroups)
                )
            );
        } else {
            $query->equals('be_group', 0);
        }

        if ($limit !== null) {
            $query->setLimit($limit);
        }

        $result = $query->execute()->toArray();
        $this->cache->set($cacheIdentifier, $result);
        return $result;
    }
}
