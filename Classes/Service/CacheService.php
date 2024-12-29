<?php

declare(strict_types=1);

namespace Xima\XimaTypo3InternalNews\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use Xima\XimaTypo3InternalNews\Configuration;

class CacheService
{
    public function __construct(
        private FrontendInterface $cache
    ) {
    }

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
        $tags = [];
        foreach ($data as $item) {
            $tags[] = 'tx_ximatypo3internalnews_domain_model_news_' . $item->getUid();
        }
        return $tags;
    }
}
