<?php

declare(strict_types=1);

namespace Xima\XimaTypo3InternalNews\Hooks;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

class DataHandler
{
    public function __construct(private FrontendInterface $cache)
    {
    }

    public function clearCachePostProc(array $params): void
    {
        $this->cache->flushByTags(array_keys($params['tags']));
    }
}
