<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "xima_typo3_internal_news".
 *
 * Copyright (C) 2024-2025 Konrad Michalik <hej@konradmichalik.dev>
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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Date.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0
 */
class Date extends AbstractEntity
{
    protected string $title = '';
    protected \DateTime|null $singleDate = null;
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
