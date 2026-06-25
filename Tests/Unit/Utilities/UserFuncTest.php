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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Utilities;

use DateTime;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Xima\XimaTypo3InternalNews\Domain\Model\{Date, News};
use Xima\XimaTypo3InternalNews\Domain\Repository\DateRepository;
use Xima\XimaTypo3InternalNews\Service\DateService;
use Xima\XimaTypo3InternalNews\Utilities\UserFunc;

/**
 * UserFuncTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class UserFuncTest extends TestCase
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
    public function dateLabelDoesNothingWhenRecordNotFound(): void
    {
        $dateRepositoryMock = $this->createMock(DateRepository::class);
        $dateRepositoryMock->method('findByUid')->willReturn(null);
        GeneralUtility::setSingletonInstance(DateRepository::class, $dateRepositoryMock);

        $userFunc = new UserFunc();
        $parameters = ['row' => ['uid' => 99, 'title' => 'Test']];
        $originalParameters = $parameters;

        $userFunc->dateLabel($parameters);

        self::assertSame($originalParameters, $parameters);
    }

    #[Test]
    public function dateLabelDoesNothingWhenDatesEmpty(): void
    {
        $date = new Date();
        $date->setTitle('Test Date');
        $date->setType('single_date');
        $date->setSingleDate(new DateTime('+1 day'));

        $dateRepositoryMock = $this->createMock(DateRepository::class);
        $dateRepositoryMock->method('findByUid')->willReturn($date);
        GeneralUtility::setSingletonInstance(DateRepository::class, $dateRepositoryMock);

        $dateServiceMock = $this->createMock(DateService::class);
        $dateServiceMock->method('getDates')->willReturn([]);
        GeneralUtility::addInstance(DateService::class, $dateServiceMock);

        GeneralUtility::addInstance(News::class, new News());

        $userFunc = new UserFunc();
        $parameters = ['row' => ['uid' => 1, 'title' => 'Test'], 'title' => 'Original'];
        $userFunc->dateLabel($parameters);

        self::assertSame('Original', $parameters['title']);
    }

    #[Test]
    public function dateLabelSetsTitleWhenSingleDateFound(): void
    {
        $date = new Date();
        $date->setTitle('Meeting');
        $date->setType('single_date');
        $date->setSingleDate(new DateTime('+1 day'));

        $dateRepositoryMock = $this->createMock(DateRepository::class);
        $dateRepositoryMock->method('findByUid')->willReturn($date);
        GeneralUtility::setSingletonInstance(DateRepository::class, $dateRepositoryMock);

        $dateEntry = [
            'type' => 'single_date',
            'date' => new DateTime('+1 day'),
        ];

        $dateServiceMock = $this->createMock(DateService::class);
        $dateServiceMock->method('getDates')->willReturn([$dateEntry]);
        GeneralUtility::addInstance(DateService::class, $dateServiceMock);

        $news = new News();
        $news->setDates(new ObjectStorage());
        GeneralUtility::addInstance(News::class, $news);

        $userFunc = new UserFunc();
        $parameters = ['row' => ['uid' => 1, 'title' => 'Meeting'], 'title' => 'Meeting'];
        $userFunc->dateLabel($parameters);

        self::assertStringContainsString('Meeting', $parameters['title']);
        self::assertStringContainsString('📅', $parameters['title']);
    }
}
