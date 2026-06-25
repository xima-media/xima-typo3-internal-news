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

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Service\NewsService;
use Xima\XimaTypo3InternalNews\ViewHelpers\IsNewViewHelper;

/**
 * IsNewViewHelperTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class IsNewViewHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        GeneralUtility::purgeInstances();
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }

    #[Test]
    public function renderReturnsTrueWhenNewsServiceIsNewReturnsTrue(): void
    {
        $news = new News();
        $news->setDates(new ObjectStorage());

        $newsServiceMock = $this->createMock(NewsService::class);
        $newsServiceMock->method('isNew')->with($news)->willReturn(true);
        GeneralUtility::addInstance(NewsService::class, $newsServiceMock);

        $viewHelper = new IsNewViewHelper();
        $viewHelper->setArguments(['news' => $news]);
        $result = $viewHelper->render();

        self::assertTrue($result);
    }

    #[Test]
    public function renderReturnsFalseWhenNewsServiceIsNewReturnsFalse(): void
    {
        $news = new News();
        $news->setDates(new ObjectStorage());

        $newsServiceMock = $this->createMock(NewsService::class);
        $newsServiceMock->method('isNew')->willReturn(false);
        GeneralUtility::addInstance(NewsService::class, $newsServiceMock);

        $viewHelper = new IsNewViewHelper();
        $viewHelper->setArguments(['news' => $news]);
        $result = $viewHelper->render();

        self::assertFalse($result);
    }

    #[Test]
    public function renderReturnsFalseWhenArgumentIsNotNewsInstance(): void
    {
        $viewHelper = new IsNewViewHelper();
        $viewHelper->setArguments(['news' => 'not-a-news-object']);
        $result = $viewHelper->render();

        self::assertFalse($result);
    }

    #[Test]
    public function initializeArgumentsRegistersNewsArgument(): void
    {
        $viewHelper = new IsNewViewHelper();
        $viewHelper->initializeArguments();

        $argumentDefinitions = $viewHelper->prepareArguments();
        self::assertArrayHasKey('news', $argumentDefinitions);
    }
}
