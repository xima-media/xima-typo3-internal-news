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
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;
use Xima\XimaTypo3InternalNews\Service\CacheService;

final class NewsRepositoryTest extends TestCase
{
    private NewsRepository $subject;
    private CacheService $cacheServiceMock;

    protected function setUp(): void
    {
        $this->cacheServiceMock = $this->getMockBuilder(CacheService::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        // Note: We can't easily unit test the full repository without TYPO3 framework
        // These tests focus on what we can test in isolation
        
        // Mock BE_USER global
        $GLOBALS['BE_USER'] = $this->createMockBackendUser();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['BE_USER']);
    }

    #[Test]
    public function repositoryCanBeInstantiated(): void
    {
        // This tests the basic structure without full TYPO3 framework
        $reflection = new \ReflectionClass(NewsRepository::class);
        
        self::assertTrue($reflection->isSubclassOf(\TYPO3\CMS\Extbase\Persistence\Repository::class));
    }

    #[Test]
    public function repositoryHasDefaultOrderings(): void
    {
        $reflection = new \ReflectionClass(NewsRepository::class);
        $property = $reflection->getProperty('defaultOrderings');
        
        self::assertTrue($property->isProtected());
    }

    #[Test]
    public function findAllByCurrentUserMethodExists(): void
    {
        $reflection = new \ReflectionClass(NewsRepository::class);
        
        self::assertTrue($reflection->hasMethod('findAllByCurrentUser'));
        
        $method = $reflection->getMethod('findAllByCurrentUser');
        self::assertTrue($method->isPublic());
        
        // Check parameters
        $parameters = $method->getParameters();
        self::assertCount(1, $parameters);
        self::assertEquals('limit', $parameters[0]->getName());
        self::assertTrue($parameters[0]->allowsNull());
    }

    #[Test]
    public function findAllByCurrentUserHasCorrectReturnType(): void
    {
        $reflection = new \ReflectionMethod(NewsRepository::class, 'findAllByCurrentUser');
        $returnType = $reflection->getReturnType();
        
        self::assertNotNull($returnType);
        
        // Check if it's a union type or named type
        if ($returnType instanceof \ReflectionUnionType) {
            $types = $returnType->getTypes();
            $typeNames = array_map(fn($type) => $type->getName(), $types);
            
            self::assertContains('array', $typeNames);
            self::assertContains('null', $typeNames);
        } else {
            // It might be a nullable array type
            self::assertTrue($returnType->allowsNull());
        }
    }

    #[Test]
    public function constructorAcceptsCacheService(): void
    {
        $reflection = new \ReflectionClass(NewsRepository::class);
        $constructor = $reflection->getConstructor();
        
        self::assertNotNull($constructor);
        
        $parameters = $constructor->getParameters();
        self::assertCount(1, $parameters);
        self::assertEquals('cache', $parameters[0]->getName());
        
        $paramType = $parameters[0]->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $paramType);
        self::assertEquals(CacheService::class, $paramType->getName());
    }

    #[Test]
    public function repositoryExtendsBaseRepository(): void
    {
        $reflection = new \ReflectionClass(NewsRepository::class);
        $parentClass = $reflection->getParentClass();
        
        self::assertNotNull($parentClass);
        self::assertEquals(\TYPO3\CMS\Extbase\Persistence\Repository::class, $parentClass->getName());
    }

    #[Test]
    public function repositoryUsesCorrectNamespace(): void
    {
        $reflection = new \ReflectionClass(NewsRepository::class);
        
        self::assertEquals('Xima\XimaTypo3InternalNews\Domain\Repository', $reflection->getNamespaceName());
        self::assertEquals('NewsRepository', $reflection->getShortName());
    }

    #[Test]
    public function repositoryIsNotFinal(): void
    {
        $reflection = new \ReflectionClass(NewsRepository::class);
        
        self::assertFalse($reflection->isFinal());
        self::assertFalse($reflection->isAbstract());
    }

    private function createMockBackendUser(): object
    {
        return new class {
            public function isAdmin(): bool
            {
                return false;
            }
            
            public array $userGroups = [1, 2, 3];
        };
    }
}