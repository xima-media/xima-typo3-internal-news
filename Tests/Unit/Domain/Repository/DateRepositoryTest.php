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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Xima\XimaTypo3InternalNews\Domain\Repository\DateRepository;

final class DateRepositoryTest extends TestCase
{
    #[Test]
    public function repositoryCanBeInstantiated(): void
    {
        $reflection = new \ReflectionClass(DateRepository::class);

        self::assertTrue($reflection->isSubclassOf(\TYPO3\CMS\Extbase\Persistence\Repository::class));
    }

    #[Test]
    public function repositoryExtendsBaseRepository(): void
    {
        $reflection = new \ReflectionClass(DateRepository::class);
        $parentClass = $reflection->getParentClass();

        self::assertNotFalse($parentClass);
        self::assertEquals(\TYPO3\CMS\Extbase\Persistence\Repository::class, $parentClass->getName());
    }

    #[Test]
    public function repositoryUsesCorrectNamespace(): void
    {
        $reflection = new \ReflectionClass(DateRepository::class);

        self::assertEquals('Xima\XimaTypo3InternalNews\Domain\Repository', $reflection->getNamespaceName());
        self::assertEquals('DateRepository', $reflection->getShortName());
    }

    #[Test]
    public function repositoryIsNotFinal(): void
    {
        $reflection = new \ReflectionClass(DateRepository::class);

        self::assertFalse($reflection->isFinal());
        self::assertFalse($reflection->isAbstract());
    }

    #[Test]
    public function repositoryHasCorrectClassStructure(): void
    {
        $reflection = new \ReflectionClass(DateRepository::class);

        // Should be a concrete class
        self::assertFalse($reflection->isInterface());
        self::assertFalse($reflection->isTrait());
    }
}
