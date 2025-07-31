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

namespace Xima\XimaTypo3InternalNews\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Xima\XimaTypo3InternalNews\Service\DateService;
use Xima\XimaTypo3InternalNews\Utilities\BackendUserHelper;

class News extends AbstractEntity
{
    protected string $title = '';
    protected int|null $tstamp = null;
    protected bool $top = false;
    protected string $description = '';
    protected FileReference|null $media = null;
    /**
    * @var ObjectStorage<\Xima\XimaTypo3InternalNews\Domain\Model\Date>
    */
    protected ObjectStorage $dates;
    /**
    * @var ObjectStorage<Category>
    */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->dates = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getMedia(): ?FileReference
    {
        return $this->media;
    }

    public function setMedia(?FileReference $media): void
    {
        $this->media = $media;
    }

    public function getDates(): ObjectStorage
    {
        return $this->dates;
    }

    public function setDates(ObjectStorage $dates): void
    {
        $this->dates = $dates;
    }

    public function getNextDate(): ?array
    {
        return DateService::getNextDate($this);
    }

    public function getNextDates(): array
    {
        return DateService::getNextDates($this);
    }

    public function isTop(): bool
    {
        return $this->top;
    }

    public function setTop(bool $top): void
    {
        $this->top = $top;
    }

    public function isTopAndNew(): bool
    {
        if (!$this->top) {
            return false;
        }
        return BackendUserHelper::checkAndSetModuleDate('internal_news/top', $this->getUid());
    }

    public function isNew(): bool
    {
        return BackendUserHelper::checkAndSetModuleDate('internal_news/read', $this->getUid());
    }

    public function getTstamp(): ?int
    {
        return $this->tstamp;
    }

    /**
    * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
    * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
    */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }
}
