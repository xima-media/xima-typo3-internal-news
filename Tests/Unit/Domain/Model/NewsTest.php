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
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Xima\XimaTypo3InternalNews\Domain\Model\Date;
use Xima\XimaTypo3InternalNews\Domain\Model\News;

final class NewsTest extends TestCase
{
    private News $subject;

    protected function setUp(): void
    {
        $this->subject = new News();
    }

    #[Test]
    public function titleCanBeSet(): void
    {
        $title = 'Test News Title';
        $this->subject->setTitle($title);
        
        self::assertEquals($title, $this->subject->getTitle());
    }

    #[Test]
    public function titleIsEmptyByDefault(): void
    {
        self::assertEquals('', $this->subject->getTitle());
    }

    #[Test]
    public function descriptionCanBeSet(): void
    {
        $description = 'Test news description';
        $this->subject->setDescription($description);
        
        self::assertEquals($description, $this->subject->getDescription());
    }

    #[Test]
    public function descriptionIsEmptyByDefault(): void
    {
        self::assertEquals('', $this->subject->getDescription());
    }

    #[Test]
    public function mediaCanBeSet(): void
    {
        $media = $this->createMock(FileReference::class);
        $this->subject->setMedia($media);
        
        self::assertEquals($media, $this->subject->getMedia());
    }

    #[Test]
    public function mediaIsNullByDefault(): void
    {
        self::assertNull($this->subject->getMedia());
    }

    #[Test]
    public function topCanBeSet(): void
    {
        $this->subject->setTop(true);
        self::assertTrue($this->subject->isTop());
        
        $this->subject->setTop(false);
        self::assertFalse($this->subject->isTop());
    }

    #[Test]
    public function topIsFalseByDefault(): void
    {
        self::assertFalse($this->subject->isTop());
    }

    #[Test]
    public function datesCanBeSet(): void
    {
        $dates = new ObjectStorage();
        $date = new Date();
        $dates->attach($date);
        
        $this->subject->setDates($dates);
        
        self::assertEquals($dates, $this->subject->getDates());
        self::assertTrue($this->subject->getDates()->contains($date));
    }

    #[Test]
    public function datesIsEmptyObjectStorageByDefault(): void
    {
        $dates = $this->subject->getDates();
        
        self::assertInstanceOf(ObjectStorage::class, $dates);
        self::assertEquals(0, $dates->count());
    }

    #[Test]
    public function categoriesCanBeSet(): void
    {
        $categories = new ObjectStorage();
        $this->subject->setCategories($categories);
        
        self::assertEquals($categories, $this->subject->getCategories());
    }

    #[Test]
    public function categoriesIsEmptyObjectStorageByDefault(): void
    {
        $categories = $this->subject->getCategories();
        
        self::assertInstanceOf(ObjectStorage::class, $categories);
        self::assertEquals(0, $categories->count());
    }

    #[Test]
    public function tstampCanBeRetrieved(): void
    {
        // Note: tstamp is protected and has no setter, so we can only test the getter
        // In real usage, this would be set by TYPO3's persistence layer
        self::assertNull($this->subject->getTstamp());
    }
}