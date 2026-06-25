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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Service;

use DateTime;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Service\{DateService, NewsService};
use Xima\XimaTypo3InternalNews\Utilities\BackendUserHelper;

/**
 * NewsServiceTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class NewsServiceTest extends TestCase
{
    private NewsService $newsService;
    private DateService $dateServiceMock;
    private BackendUserHelper $backendUserHelperMock;

    protected function setUp(): void
    {
        $this->dateServiceMock = $this->createMock(DateService::class);
        $this->backendUserHelperMock = $this->createMock(BackendUserHelper::class);
        $this->newsService = new NewsService($this->dateServiceMock, $this->backendUserHelperMock);
    }

    #[Test]
    public function getNextDateDelegatesToDateService(): void
    {
        $news = $this->createNews();
        $expected = ['date' => new DateTime('+1 day'), 'id' => 1, 'title' => 'Test', 'type' => 'single_date', 'newsId' => 0];

        $this->dateServiceMock
            ->method('getNextDate')
            ->with($news)
            ->willReturn($expected);

        $result = $this->newsService->getNextDate($news);

        self::assertSame($expected, $result);
    }

    #[Test]
    public function getNextDateReturnsNullWhenDateServiceReturnsNull(): void
    {
        $news = $this->createNews();

        $this->dateServiceMock
            ->method('getNextDate')
            ->willReturn(null);

        $result = $this->newsService->getNextDate($news);

        self::assertNull($result);
    }

    #[Test]
    public function getNextDatesDelegatesToDateService(): void
    {
        $news = $this->createNews();
        $expected = [['date' => new DateTime('+1 day'), 'id' => 1, 'title' => 'Test', 'type' => 'single_date', 'newsId' => 0]];

        $this->dateServiceMock
            ->method('getNextDates')
            ->with($news)
            ->willReturn($expected);

        $result = $this->newsService->getNextDates($news);

        self::assertSame($expected, $result);
    }

    #[Test]
    public function isNewDelegatesToBackendUserHelper(): void
    {
        $news = $this->createNews();

        $this->backendUserHelperMock
            ->expects(self::once())
            ->method('checkAndSetModuleDate')
            ->with('internal_news/read', $news->getUid())
            ->willReturn(true);

        $result = $this->newsService->isNew($news);

        self::assertTrue($result);
    }

    #[Test]
    public function isNewReturnsFalseWhenBackendUserHelperReturnsFalse(): void
    {
        $news = $this->createNews();

        $this->backendUserHelperMock
            ->method('checkAndSetModuleDate')
            ->willReturn(false);

        $result = $this->newsService->isNew($news);

        self::assertFalse($result);
    }

    #[Test]
    public function isTopAndNewReturnsFalseForNonTopNews(): void
    {
        $news = $this->createNews();
        $news->setTop(false);

        $this->backendUserHelperMock
            ->expects(self::never())
            ->method('checkAndSetModuleDate');

        $result = $this->newsService->isTopAndNew($news);

        self::assertFalse($result);
    }

    #[Test]
    public function isTopAndNewCallsBackendUserHelperForTopNews(): void
    {
        $news = $this->createNews();
        $news->setTop(true);

        $this->backendUserHelperMock
            ->expects(self::once())
            ->method('checkAndSetModuleDate')
            ->with('internal_news/top', $news->getUid())
            ->willReturn(true);

        $result = $this->newsService->isTopAndNew($news);

        self::assertTrue($result);
    }

    #[Test]
    public function isTopAndNewReturnsFalseWhenBackendUserHelperReturnsFalseForTopNews(): void
    {
        $news = $this->createNews();
        $news->setTop(true);

        $this->backendUserHelperMock
            ->method('checkAndSetModuleDate')
            ->willReturn(false);

        $result = $this->newsService->isTopAndNew($news);

        self::assertFalse($result);
    }

    private function createNews(): News
    {
        $news = new News();
        $news->setDates(new ObjectStorage());

        return $news;
    }
}
