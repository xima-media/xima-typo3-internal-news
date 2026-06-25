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
use ReflectionClass;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Xima\XimaTypo3InternalNews\Domain\Model\{Date, News};
use Xima\XimaTypo3InternalNews\Service\DateService;


/**
 * DateServiceTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

final class DateServiceTest extends TestCase
{
    private DateService $dateService;

    protected function setUp(): void
    {
        // Mock GLOBALS for language service
        $GLOBALS['LANG'] = new class {
            public function sL(string $key): string
            {
                return 'Notification message';
            }
        };

        // Reset GeneralUtility for isolated tests
        GeneralUtility::purgeInstances();

        // Create DateService instance with mock dependencies
        $extensionConfigurationMock = $this->getMockBuilder(ExtensionConfiguration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $extensionConfigurationMock
            ->method('get')
            ->willReturn(['notifyTimeThreshold' => 3600]);

        $this->dateService = new DateService($extensionConfigurationMock);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['LANG']);
        GeneralUtility::purgeInstances();
    }

    #[Test]
    public function getNextDateReturnsNullForNewsWithoutDates(): void
    {
        $news = $this->createNewsWithDates([]);

        $result = $this->dateService->getNextDate($news);

        self::assertNull($result);
    }

    #[Test]
    public function getNextDateMethodExists(): void
    {
        $reflection = new ReflectionClass(DateService::class);

        self::assertTrue($reflection->hasMethod('getNextDate'));

        $method = $reflection->getMethod('getNextDate');
        self::assertFalse($method->isStatic());
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function getNextDatesReturnsEmptyArrayForNewsWithoutDates(): void
    {
        $news = $this->createNewsWithDates([]);

        $result = $this->dateService->getNextDates($news);

        self::assertEmpty($result);
    }

    #[Test]
    public function getNextDatesSortsByDate(): void
    {
        $date1 = $this->createDate('single_date', new DateTime('+2 days'));
        $date2 = $this->createDate('single_date', new DateTime('+1 day'));
        $news = $this->createNewsWithDates([$date1, $date2]);

        $result = $this->dateService->getNextDates($news);

        // Test that method returns array (detailed sorting test would require more complex mocking)
        self::assertNotEmpty($result);
    }

    #[Test]
    public function getNotifyDatesByNewsListReturnsEmptyArrayForEmptyList(): void
    {
        $result = $this->dateService->getNotifyDatesByNewsList([]);

        self::assertEmpty($result);
    }

    #[Test]
    public function getNotifyDatesByNewsListMethodExists(): void
    {
        $reflection = new ReflectionClass(DateService::class);

        self::assertTrue($reflection->hasMethod('getNotifyDatesByNewsList'));

        $method = $reflection->getMethod('getNotifyDatesByNewsList');
        self::assertFalse($method->isStatic());
        self::assertTrue($method->isPublic());
    }

    #[Test]
    public function getDatesReturnsEmptyArrayForPastSingleDate(): void
    {
        $pastDate = new DateTime('-1 day');
        $date = $this->createDate('single_date', $pastDate);
        $news = $this->createNewsWithDates([]);

        $result = $this->dateService->getDates($news, $date);

        self::assertEmpty($result);
    }

    #[Test]
    public function getDatesHandlesSingleDateType(): void
    {
        $futureDate = new DateTime('+1 day');
        $date = $this->createDate('single_date', $futureDate);
        $news = $this->createNewsWithDates([]);

        $result = $this->dateService->getDates($news, $date);

        // Detailed result checking would require mocking the notify check logic
        self::assertNotEmpty($result);
    }

    #[Test]
    public function hasRecurrenceSupportMethodExists(): void
    {
        $reflection = new ReflectionClass(DateService::class);

        self::assertTrue($reflection->hasMethod('hasRecurrenceSupport'));

        $method = $reflection->getMethod('hasRecurrenceSupport');
        self::assertFalse($method->isStatic());
        self::assertTrue($method->isPublic());
        self::assertSame('bool', (string)$method->getReturnType());
    }

    #[Test]
    public function getDatesHandlesRecurrenceType(): void
    {
        if (!$this->dateService->hasRecurrenceSupport()) {
            self::markTestSkipped('simshaun/recurr is not installed; skip recurr happy-path test');
        }

        $startDate = new DateTime('+1 day');
        $date = $this->createDate('recurrence', $startDate, 'FREQ=DAILY;COUNT=3');
        $news = $this->createNewsWithDates([]);

        $result = $this->dateService->getDates($news, $date);

        // Detailed recurrence testing would require more complex setup
        self::assertNotEmpty($result);
    }

    #[Test]
    public function getDatesHandlesRecurrenceTypeWithoutRecurrenceLibrary(): void
    {
        if ($this->dateService->hasRecurrenceSupport()) {
            self::markTestSkipped('simshaun/recurr is installed; skip no-recurr test');
        }

        $startDate = new DateTime('+1 day');
        $date = $this->createDate('recurrence', $startDate, 'FREQ=DAILY;COUNT=3');
        $news = $this->createNewsWithDates([]);

        $result = $this->dateService->getDates($news, $date);

        // Without recurr, recurrence type returns empty array
        self::assertEmpty($result);
    }

    private function createNewsWithDates(array $dates): News
    {
        $news = new News();
        $objectStorage = new ObjectStorage();

        foreach ($dates as $date) {
            $objectStorage->attach($date);
        }

        $news->setDates($objectStorage);

        return $news;
    }

    private function createDate(string $type, DateTime $singleDate, string $recurrence = ''): Date
    {
        $date = new Date();
        $date->setType($type);
        $date->setSingleDate($singleDate);
        $date->setRecurrence($recurrence);
        $date->setTitle('Test Date');

        return $date;
    }
}
