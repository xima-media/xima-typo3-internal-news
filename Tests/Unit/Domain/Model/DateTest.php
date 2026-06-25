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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Domain\Model;

use DateTime;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Xima\XimaTypo3InternalNews\Domain\Model\Date;


/**
 * DateTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

final class DateTest extends TestCase
{
    private Date $subject;

    protected function setUp(): void
    {
        $this->subject = new Date();
    }

    #[Test]
    public function titleCanBeSet(): void
    {
        $title = 'Test Date Title';
        $this->subject->setTitle($title);

        self::assertEquals($title, $this->subject->getTitle());
    }

    #[Test]
    public function titleIsEmptyByDefault(): void
    {
        self::assertEquals('', $this->subject->getTitle());
    }

    #[Test]
    public function singleDateCanBeSet(): void
    {
        $date = new DateTime('2025-12-31 23:59:59');
        $this->subject->setSingleDate($date);

        self::assertEquals($date, $this->subject->getSingleDate());
    }

    #[Test]
    public function singleDateIsNullByDefault(): void
    {
        self::assertNull($this->subject->getSingleDate());
    }

    #[Test]
    public function recurrenceCanBeSet(): void
    {
        $recurrence = 'FREQ=WEEKLY;BYDAY=MO';
        $this->subject->setRecurrence($recurrence);

        self::assertEquals($recurrence, $this->subject->getRecurrence());
    }

    #[Test]
    public function recurrenceIsEmptyByDefault(): void
    {
        self::assertEquals('', $this->subject->getRecurrence());
    }

    #[Test]
    public function notifyCanBeSet(): void
    {
        $this->subject->setNotify(true);
        self::assertTrue($this->subject->isNotify());

        $this->subject->setNotify(false);
        self::assertFalse($this->subject->isNotify());
    }

    #[Test]
    public function notifyIsFalseByDefault(): void
    {
        self::assertFalse($this->subject->isNotify());
    }

    #[Test]
    public function typeCanBeSet(): void
    {
        $type = 'single_date';
        $this->subject->setType($type);

        self::assertEquals($type, $this->subject->getType());
    }

    #[Test]
    public function typeIsEmptyByDefault(): void
    {
        self::assertEquals('', $this->subject->getType());
    }

    #[Test]
    public function notifyTypeCanBeSet(): void
    {
        $notifyType = 'warning';
        $this->subject->setNotifyType($notifyType);

        self::assertEquals($notifyType, $this->subject->getNotifyType());
    }

    #[Test]
    public function notifyTypeIsEmptyByDefault(): void
    {
        self::assertEquals('', $this->subject->getNotifyType());
    }

    #[Test]
    public function notifyMessageCanBeSet(): void
    {
        $message = 'Test notification message';
        $this->subject->setNotifyMessage($message);

        self::assertEquals($message, $this->subject->getNotifyMessage());
    }

    #[Test]
    public function notifyMessageIsEmptyByDefault(): void
    {
        self::assertEquals('', $this->subject->getNotifyMessage());
    }
}
