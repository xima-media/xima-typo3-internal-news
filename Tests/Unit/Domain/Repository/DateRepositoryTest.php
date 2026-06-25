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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Xima\XimaTypo3InternalNews\Domain\Repository\DateRepository;

/**
 * DateRepositoryTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class DateRepositoryTest extends TestCase
{
    #[Test]
    public function repositoryCanBeInstantiated(): void
    {
        $reflection = new ReflectionClass(DateRepository::class);

        self::assertTrue($reflection->isSubclassOf(\TYPO3\CMS\Extbase\Persistence\Repository::class));
    }

    #[Test]
    public function repositoryExtendsBaseRepository(): void
    {
        $reflection = new ReflectionClass(DateRepository::class);
        $parentClass = $reflection->getParentClass();

        self::assertNotFalse($parentClass);
        self::assertEquals(\TYPO3\CMS\Extbase\Persistence\Repository::class, $parentClass->getName());
    }

    #[Test]
    public function repositoryUsesCorrectNamespace(): void
    {
        $reflection = new ReflectionClass(DateRepository::class);

        self::assertEquals('Xima\XimaTypo3InternalNews\Domain\Repository', $reflection->getNamespaceName());
        self::assertEquals('DateRepository', $reflection->getShortName());
    }

    #[Test]
    public function repositoryIsNotFinal(): void
    {
        $reflection = new ReflectionClass(DateRepository::class);

        self::assertFalse($reflection->isFinal());
        self::assertFalse($reflection->isAbstract());
    }

    #[Test]
    public function repositoryHasCorrectClassStructure(): void
    {
        $reflection = new ReflectionClass(DateRepository::class);

        // Should be a concrete class
        self::assertFalse($reflection->isInterface());
        self::assertFalse($reflection->isTrait());
    }
}
