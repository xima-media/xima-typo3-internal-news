<?php

declare(strict_types=1);

namespace Xima\XimaTypo3InternalNews\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class NewsRepository extends Repository
{
    protected $defaultOrderings = [
        'tstamp' => QueryInterface::ORDER_DESCENDING,
    ];

    public function findAllByCurrentUser(int|null $limit = null): QueryResultInterface|null
    {
        if ($GLOBALS['BE_USER']->isAdmin()) {
            return $this->findAll();
        }
        $userGroups = array_keys($GLOBALS['BE_USER']->userGroups);

        $query = $this->createQuery();
        $query->matching(
            $query->logicalOr(
                $query->equals('be_group', 0),
                $query->in('be_group', $userGroups)
            )
        );

        if ($limit) {
            $query->setLimit($limit);
        }
        return $query->execute();
    }
}
