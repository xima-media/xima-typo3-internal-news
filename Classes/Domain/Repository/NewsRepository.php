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

namespace Xima\XimaTypo3InternalNews\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\{QueryInterface, Repository};
use Xima\XimaTypo3InternalNews\Service\CacheService;

use function is_array;
use function is_object;


/**
 * NewsRepository.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

class NewsRepository extends Repository
{
    protected $defaultOrderings = [
        'tstamp' => QueryInterface::ORDER_DESCENDING,
    ];

    public function __construct(
        private readonly CacheService $cache,
    ) {
        parent::__construct();
    }

    public function findAllByCurrentUser(?int $limit = null): ?array
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

        if ([] !== $userGroups) {
            $query->matching(
                $query->logicalOr(
                    $query->equals('be_group', 0),
                    $query->in('be_group', $userGroups),
                ),
            );
        } else {
            $query->equals('be_group', 0);
        }

        if (null !== $limit) {
            $query->setLimit($limit);
        }

        $result = $query->execute()->toArray();
        $this->cache->set($cacheIdentifier, $result);

        return $result;
    }
}
