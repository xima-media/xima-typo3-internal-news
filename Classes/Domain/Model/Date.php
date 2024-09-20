<?php

declare(strict_types=1);

namespace Xima\XimaTypo3InternalNews\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Date extends AbstractEntity
{
    protected string $title = '';
    protected \DateTime|null $singleDate = null;
    protected string $recurrence = '';
    protected bool $notify = false;
    protected string $type = '';
    protected string $notifyType = '';
    protected string $notifyMessage = '';

    public function __construct()
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSingleDate(): ?\DateTime
    {
        return $this->singleDate;
    }

    public function setSingleDate(?\DateTime $date): void
    {
        $this->singleDate = $date;
    }

    public function getRecurrence(): string
    {
        return $this->recurrence;
    }

    public function setRecurrence(string $recurrence): void
    {
        $this->recurrence = $recurrence;
    }

    public function isNotify(): bool
    {
        return $this->notify;
    }

    public function setNotify(bool $notify): void
    {
        $this->notify = $notify;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getNotifyType(): string
    {
        return $this->notifyType;
    }

    public function setNotifyType(string $notifyType): void
    {
        $this->notifyType = $notifyType;
    }

    public function getNotifyMessage(): string
    {
        return $this->notifyMessage;
    }

    public function setNotifyMessage(string $notifyMessage): void
    {
        $this->notifyMessage = $notifyMessage;
    }
}
