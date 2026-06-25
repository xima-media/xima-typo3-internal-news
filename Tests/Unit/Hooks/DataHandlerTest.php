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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Hooks;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use Xima\XimaTypo3InternalNews\Hooks\DataHandler;

/**
 * DataHandlerTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class DataHandlerTest extends TestCase
{
    #[Test]
    public function clearCachePostProcFlushesTagsFromParams(): void
    {
        $cacheMock = $this->createMock(FrontendInterface::class);
        $cacheMock->expects(self::once())
            ->method('flushByTags')
            ->with(['tag_a', 'tag_b']);

        $dataHandler = new DataHandler($cacheMock);
        $dataHandler->clearCachePostProc(['tags' => ['tag_a' => 1, 'tag_b' => 1]]);
    }

    #[Test]
    public function clearCachePostProcHandlesEmptyTags(): void
    {
        $cacheMock = $this->createMock(FrontendInterface::class);
        $cacheMock->expects(self::once())
            ->method('flushByTags')
            ->with([]);

        $dataHandler = new DataHandler($cacheMock);
        $dataHandler->clearCachePostProc(['tags' => []]);
    }
}
