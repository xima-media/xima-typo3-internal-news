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
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;
use Xima\XimaTypo3InternalNews\Service\CacheService;

/**
 * NewsRepositoryTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class NewsRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
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
        $reflection = new ReflectionClass(NewsRepository::class);

        self::assertTrue($reflection->isSubclassOf(\TYPO3\CMS\Extbase\Persistence\Repository::class));
    }

    #[Test]
    public function repositoryHasDefaultOrderings(): void
    {
        $reflection = new ReflectionClass(NewsRepository::class);
        $property = $reflection->getProperty('defaultOrderings');

        self::assertTrue($property->isProtected());
    }

    #[Test]
    public function findAllByCurrentUserMethodExists(): void
    {
        $reflection = new ReflectionClass(NewsRepository::class);

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
        $reflection = new ReflectionMethod(NewsRepository::class, 'findAllByCurrentUser');
        $returnType = $reflection->getReturnType();

        self::assertNotNull($returnType);

        // Check if it's a union type or named type
        if ($returnType instanceof ReflectionUnionType) {
            $types = $returnType->getTypes();
            $typeNames = array_map(static fn ($type) => $type->getName(), $types);

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
        $reflection = new ReflectionClass(NewsRepository::class);
        $constructor = $reflection->getConstructor();

        self::assertNotNull($constructor);

        $parameters = $constructor->getParameters();
        self::assertCount(1, $parameters);
        self::assertEquals('cache', $parameters[0]->getName());

        $paramType = $parameters[0]->getType();
        self::assertInstanceOf(ReflectionNamedType::class, $paramType);
        self::assertEquals(CacheService::class, $paramType->getName());
    }

    #[Test]
    public function repositoryExtendsBaseRepository(): void
    {
        $reflection = new ReflectionClass(NewsRepository::class);
        $parentClass = $reflection->getParentClass();

        self::assertNotFalse($parentClass);
        self::assertEquals(\TYPO3\CMS\Extbase\Persistence\Repository::class, $parentClass->getName());
    }

    #[Test]
    public function repositoryUsesCorrectNamespace(): void
    {
        $reflection = new ReflectionClass(NewsRepository::class);

        self::assertEquals('Xima\XimaTypo3InternalNews\Domain\Repository', $reflection->getNamespaceName());
        self::assertEquals('NewsRepository', $reflection->getShortName());
    }

    #[Test]
    public function repositoryIsNotFinal(): void
    {
        $reflection = new ReflectionClass(NewsRepository::class);

        self::assertFalse($reflection->isFinal());
        self::assertFalse($reflection->isAbstract());
    }

    #[Test]
    public function findAllByCurrentUserReturnsEmptyArrayWhenNoBackendUser(): void
    {
        unset($GLOBALS['BE_USER']);

        $cacheMock = $this->createMock(\TYPO3\CMS\Core\Cache\Frontend\FrontendInterface::class);
        $cacheService = new CacheService($cacheMock);

        $reflection = new ReflectionClass(NewsRepository::class);
        $repository = $reflection->newInstanceWithoutConstructor();

        // Set the cache service via reflection
        $cacheProperty = $reflection->getProperty('cache');
        $cacheProperty->setValue($repository, $cacheService);

        $result = $repository->findAllByCurrentUser();

        self::assertSame([], $result);
    }

    #[Test]
    public function findAllByCurrentUserReturnsEmptyArrayWhenBackendUserIsNotObject(): void
    {
        $GLOBALS['BE_USER'] = 'not-an-object';

        $cacheMock = $this->createMock(\TYPO3\CMS\Core\Cache\Frontend\FrontendInterface::class);
        $cacheService = new CacheService($cacheMock);

        $reflection = new ReflectionClass(NewsRepository::class);
        $repository = $reflection->newInstanceWithoutConstructor();

        $cacheProperty = $reflection->getProperty('cache');
        $cacheProperty->setValue($repository, $cacheService);

        $result = $repository->findAllByCurrentUser();

        self::assertSame([], $result);

        $GLOBALS['BE_USER'] = $this->createMockBackendUser();
    }

    #[Test]
    public function findAllByCurrentUserReturnsEmptyArrayWhenUserGroupsNotArray(): void
    {
        $GLOBALS['BE_USER'] = new class {
            public $userGroups = 'not-an-array';

            public function isAdmin(): bool
            {
                return false;
            }
        };

        $cacheMock = $this->createMock(\TYPO3\CMS\Core\Cache\Frontend\FrontendInterface::class);
        $cacheService = new CacheService($cacheMock);

        $reflection = new ReflectionClass(NewsRepository::class);
        $repository = $reflection->newInstanceWithoutConstructor();

        $cacheProperty = $reflection->getProperty('cache');
        $cacheProperty->setValue($repository, $cacheService);

        $result = $repository->findAllByCurrentUser();

        self::assertSame([], $result);

        $GLOBALS['BE_USER'] = $this->createMockBackendUser();
    }

    #[Test]
    public function findAllByCurrentUserReturnsCachedResultWhenCacheHit(): void
    {
        $GLOBALS['BE_USER'] = new class {
            public array $userGroups = [1 => ['uid' => 1], 2 => ['uid' => 2]];

            public function isAdmin(): bool
            {
                return false;
            }
        };

        $cachedData = [new \Xima\XimaTypo3InternalNews\Domain\Model\News()];

        $cacheServiceMock = $this->createMock(CacheService::class);
        $cacheServiceMock->method('generateCacheIdentifier')->willReturn('test-cache-id');
        $cacheServiceMock->method('has')->with('test-cache-id')->willReturn(true);
        $cacheServiceMock->method('get')->with('test-cache-id')->willReturn($cachedData);

        $reflection = new ReflectionClass(NewsRepository::class);
        $repository = $reflection->newInstanceWithoutConstructor();

        $cacheProperty = $reflection->getProperty('cache');
        $cacheProperty->setValue($repository, $cacheServiceMock);

        $result = $repository->findAllByCurrentUser();

        self::assertSame($cachedData, $result);

        $GLOBALS['BE_USER'] = $this->createMockBackendUser();
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
