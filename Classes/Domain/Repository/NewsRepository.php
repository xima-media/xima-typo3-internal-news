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

namespace Xima\XimaTypo3InternalNews\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Xima\XimaTypo3InternalNews\Service\CacheService;

/**
 * NewsRepository.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0
 */
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
        $userGroups = array_keys($GLOBALS['BE_USER']->userGroups);
        $cacheIdentifier = $this->cache->generateCacheIdentifier($userGroups);
        if ($this->cache->has($cacheIdentifier)) {
            return $this->cache->get($cacheIdentifier);
        }

        if ($GLOBALS['BE_USER']->isAdmin()) {
            $result = $this->findAll()->toArray();
            $this->cache->set($cacheIdentifier, $result);
            return $result;
        }

        $query = $this->createQuery();

        if (!empty($userGroups)) {
            $query->matching(
                $query->logicalOr(
                    $query->equals('be_group', 0),
                    $query->in('be_group', $userGroups)
                )
            );
        } else {
            $query->equals('be_group', 0);
        }

        if ($limit) {
            $query->setLimit($limit);
        }

        $result = $query->execute()->toArray();
        $this->cache->set($cacheIdentifier, $result);
        return $result;
    }
}
