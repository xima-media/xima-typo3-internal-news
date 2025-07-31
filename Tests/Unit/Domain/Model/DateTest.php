<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "xima_typo3_internal_news".
 *
 * Copyright (C) 2025 Konrad Michalik <hej@konradmichalik.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Xima\XimaTypo3InternalNews\Domain\Model\Date;

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
        $date = new \DateTime('2025-12-31 23:59:59');
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