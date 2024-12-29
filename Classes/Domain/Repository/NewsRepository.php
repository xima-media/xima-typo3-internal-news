<?php

declare(strict_types=1);

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
