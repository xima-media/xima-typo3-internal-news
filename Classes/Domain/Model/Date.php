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

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Date.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
class Date extends AbstractEntity
{
    protected string $title = '';
    protected ?DateTime $singleDate = null;
    protected string $recurrence = '';
    protected bool $notify = false;
    protected string $type = '';
    protected string $notifyType = '';
    protected string $notifyMessage = '';

    public function __construct() {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSingleDate(): ?DateTime
    {
        return $this->singleDate;
    }

    public function setSingleDate(?DateTime $date): void
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
