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

namespace Xima\XimaTypo3InternalNews\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\{Category, FileReference};
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * News.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
class News extends AbstractEntity
{
    protected string $title = '';
    protected ?int $tstamp = null;
    protected bool $top = false;
    protected string $description = '';
    protected ?FileReference $media = null;
    /**
     * @var ObjectStorage<Date>
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

    public function isTop(): bool
    {
        return $this->top;
    }

    public function setTop(bool $top): void
    {
        $this->top = $top;
    }

    public function getTstamp(): ?int
    {
        return $this->tstamp;
    }

    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }
}
