<?php

declare(strict_types=1);

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
    protected FileReference|null $image = null;
    /**
    * @var ObjectStorage<\Xima\XimaTypo3InternalNews\Domain\Model\Date>
    */
    protected ObjectStorage $dates;
    protected News|null $news = null;
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

    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    public function setImage(?FileReference $image): void
    {
        $this->image = $image;
    }

    public function getDates(): ObjectStorage
    {
        return $this->dates;
    }

    public function setDates(ObjectStorage $dates): void
    {
        $this->dates = $dates;
    }

    /**
    * @return \Xima\XimaTypo3InternalNews\Domain\Model\News|null
    */
    public function getNews(): ?News
    {
        return $this->news;
    }

    /**
    * @param \Xima\XimaTypo3InternalNews\Domain\Model\News|null $news
    */
    public function setNews(?News $news): void
    {
        $this->news = $news;
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
