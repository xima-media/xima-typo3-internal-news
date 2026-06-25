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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\ViewHelpers;

use DateTime;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Service\NewsService;
use Xima\XimaTypo3InternalNews\ViewHelpers\NextDateViewHelper;

/**
 * NextDateViewHelperTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class NextDateViewHelperTest extends TestCase
{
    protected function setUp(): void
    {
        GeneralUtility::purgeInstances();
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
    }

    #[Test]
    public function renderReturnsArrayWhenNewsServiceGetNextDateReturnsArray(): void
    {
        $news = new News();
        $news->setDates(new ObjectStorage());
        $expected = ['date' => new DateTime('+1 day'), 'id' => 1, 'title' => 'Test', 'type' => 'single_date', 'newsId' => 0];

        /** @var NewsService&MockObject $newsServiceMock */
        $newsServiceMock = $this->createMock(NewsService::class);
        $newsServiceMock->expects(self::once())->method('getNextDate')->with($news)->willReturn($expected);
        GeneralUtility::addInstance(NewsService::class, $newsServiceMock);

        $viewHelper = new NextDateViewHelper();
        $viewHelper->setArguments(['news' => $news]);
        $result = $viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    public function renderReturnsNullWhenNewsServiceGetNextDateReturnsNull(): void
    {
        $news = new News();
        $news->setDates(new ObjectStorage());

        /** @var NewsService&MockObject $newsServiceMock */
        $newsServiceMock = $this->createMock(NewsService::class);
        $newsServiceMock->method('getNextDate')->willReturn(null);
        GeneralUtility::addInstance(NewsService::class, $newsServiceMock);

        $viewHelper = new NextDateViewHelper();
        $viewHelper->setArguments(['news' => $news]);
        $result = $viewHelper->render();

        self::assertNull($result);
    }

    #[Test]
    public function renderReturnsNullWhenArgumentIsNotNewsInstance(): void
    {
        $viewHelper = new NextDateViewHelper();
        $viewHelper->setArguments(['news' => 'not-a-news-object']);
        $result = $viewHelper->render();

        self::assertNull($result);
    }

    #[Test]
    public function initializeArgumentsRegistersNewsArgument(): void
    {
        $viewHelper = new NextDateViewHelper();
        $viewHelper->initializeArguments();

        $argumentDefinitions = $viewHelper->prepareArguments();
        self::assertArrayHasKey('news', $argumentDefinitions);
    }
}
