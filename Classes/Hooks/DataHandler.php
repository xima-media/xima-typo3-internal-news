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

namespace Xima\XimaTypo3InternalNews\Hooks;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

/**
 * DataHandler.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
class DataHandler
{
    public function __construct(private readonly FrontendInterface $cache) {}

    public function clearCachePostProc(array $params): void
    {
        $this->cache->flushByTags(array_keys($params['tags']));
    }
}
