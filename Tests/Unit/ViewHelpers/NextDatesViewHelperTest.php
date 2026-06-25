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
use Xima\XimaTypo3InternalNews\ViewHelpers\NextDatesViewHelper;

/**
 * NextDatesViewHelperTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class NextDatesViewHelperTest extends TestCase
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
    public function renderReturnsArrayWhenNewsServiceGetNextDatesReturnsArray(): void
    {
        $news = new News();
        $news->setDates(new ObjectStorage());
        $expected = [
            ['date' => new DateTime('+1 day'), 'id' => 1, 'title' => 'Test', 'type' => 'single_date', 'newsId' => 0],
            ['date' => new DateTime('+2 days'), 'id' => 2, 'title' => 'Test 2', 'type' => 'single_date', 'newsId' => 0],
        ];

        /** @var NewsService&MockObject $newsServiceMock */
        $newsServiceMock = $this->createMock(NewsService::class);
        $newsServiceMock->expects(self::once())->method('getNextDates')->with($news)->willReturn($expected);
        GeneralUtility::addInstance(NewsService::class, $newsServiceMock);

        $viewHelper = new NextDatesViewHelper();
        $viewHelper->setArguments(['news' => $news]);
        $result = $viewHelper->render();

        self::assertSame($expected, $result);
    }

    #[Test]
    public function renderReturnsEmptyArrayWhenNewsServiceGetNextDatesReturnsEmpty(): void
    {
        $news = new News();
        $news->setDates(new ObjectStorage());

        /** @var NewsService&MockObject $newsServiceMock */
        $newsServiceMock = $this->createMock(NewsService::class);
        $newsServiceMock->method('getNextDates')->willReturn([]);
        GeneralUtility::addInstance(NewsService::class, $newsServiceMock);

        $viewHelper = new NextDatesViewHelper();
        $viewHelper->setArguments(['news' => $news]);
        $result = $viewHelper->render();

        self::assertSame([], $result);
    }

    #[Test]
    public function renderReturnsEmptyArrayWhenArgumentIsNotNewsInstance(): void
    {
        $viewHelper = new NextDatesViewHelper();
        $viewHelper->setArguments(['news' => 42]);
        $result = $viewHelper->render();

        self::assertSame([], $result);
    }

    #[Test]
    public function initializeArgumentsRegistersNewsArgument(): void
    {
        $viewHelper = new NextDatesViewHelper();
        $viewHelper->initializeArguments();

        $argumentDefinitions = $viewHelper->prepareArguments();
        self::assertArrayHasKey('news', $argumentDefinitions);
    }
}
