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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Utilities;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use Xima\XimaTypo3InternalNews\Utilities\BackendUserHelper;

use function count;

/**
 * BackendUserHelperTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class BackendUserHelperTest extends TestCase
{
    private BackendUserHelper $backendUserHelper;

    protected function setUp(): void
    {
        // Mock BE_USER global
        $GLOBALS['BE_USER'] = $this->createMockBackendUser();

        $this->backendUserHelper = new BackendUserHelper();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['BE_USER']);
    }

    #[Test]
    public function checkAndSetModuleDateReturnsTrueForNewValue(): void
    {
        $moduleName = 'test_module';
        $value = 123;

        $result = $this->backendUserHelper->checkAndSetModuleDate($moduleName, $value);

        self::assertTrue($result);
    }

    #[Test]
    public function checkAndSetModuleDateReturnsFalseForExistingValue(): void
    {
        $moduleName = 'test_module';
        $value = 123;

        // First call should return true (new value)
        $firstResult = $this->backendUserHelper->checkAndSetModuleDate($moduleName, $value);
        self::assertTrue($firstResult);

        // Second call should return false (existing value)
        $secondResult = $this->backendUserHelper->checkAndSetModuleDate($moduleName, $value);
        self::assertFalse($secondResult);
    }

    #[Test]
    public function checkAndSetModuleDateHandlesStringValues(): void
    {
        $moduleName = 'test_module';
        $value = 'test_string';

        $result = $this->backendUserHelper->checkAndSetModuleDate($moduleName, $value);

        self::assertTrue($result);
    }

    #[Test]
    public function checkAndSetModuleDateHandlesDifferentModules(): void
    {
        $value = 123;

        $result1 = $this->backendUserHelper->checkAndSetModuleDate('module1', $value);
        $result2 = $this->backendUserHelper->checkAndSetModuleDate('module2', $value);

        // Same value in different modules should both return true
        self::assertTrue($result1);
        self::assertTrue($result2);
    }

    #[Test]
    public function checkAndSetModuleDateHandlesMultipleValuesInSameModule(): void
    {
        $moduleName = 'test_module';

        $result1 = $this->backendUserHelper->checkAndSetModuleDate($moduleName, 123);
        $result2 = $this->backendUserHelper->checkAndSetModuleDate($moduleName, 456);
        $result3 = $this->backendUserHelper->checkAndSetModuleDate($moduleName, 123); // Duplicate

        self::assertTrue($result1);   // New value
        self::assertTrue($result2);   // New value
        self::assertFalse($result3);  // Existing value
    }

    #[Test]
    public function checkAndSetModuleDateHandlesNullModuleData(): void
    {
        $moduleName = 'empty_module';
        $value = 123;

        // BE_USER will return null for unknown modules
        $result = $this->backendUserHelper->checkAndSetModuleDate($moduleName, $value);

        self::assertTrue($result);
    }

    #[Test]
    public function methodIsStatic(): void
    {
        $reflection = new ReflectionMethod(BackendUserHelper::class, 'checkAndSetModuleDate');

        self::assertFalse($reflection->isStatic());
        self::assertTrue($reflection->isPublic());
    }

    #[Test]
    public function methodHasCorrectParameters(): void
    {
        $reflection = new ReflectionMethod(BackendUserHelper::class, 'checkAndSetModuleDate');
        $parameters = $reflection->getParameters();

        self::assertCount(2, $parameters);
        self::assertEquals('moduleName', $parameters[0]->getName());
        self::assertEquals('value', $parameters[1]->getName());

        // Check parameter types
        $firstParamType = $parameters[0]->getType();
        self::assertInstanceOf(ReflectionNamedType::class, $firstParamType);
        self::assertEquals('string', $firstParamType->getName());

        // Second parameter should be mixed type
        $secondParamType = $parameters[1]->getType();
        if ($secondParamType instanceof ReflectionUnionType) {
            // Union type with multiple types
            self::assertGreaterThan(1, count($secondParamType->getTypes()));
        } else {
            // Mixed type or single type
            self::assertNotNull($secondParamType);
        }
    }

    #[Test]
    public function methodHasCorrectReturnType(): void
    {
        $reflection = new ReflectionMethod(BackendUserHelper::class, 'checkAndSetModuleDate');
        $returnType = $reflection->getReturnType();

        self::assertInstanceOf(ReflectionNamedType::class, $returnType);
        self::assertEquals('bool', $returnType->getName());
    }

    #[Test]
    public function checkAndSetModuleDateReturnsFalseWhenNoBackendUser(): void
    {
        unset($GLOBALS['BE_USER']);

        $result = $this->backendUserHelper->checkAndSetModuleDate('test_module', 123);

        self::assertFalse($result);
    }

    private function createMockBackendUser(): object
    {
        return new class {
            private array $moduleData = [];

            public function getModuleData(string $moduleName): ?array
            {
                return $this->moduleData[$moduleName] ?? null;
            }

            public function pushModuleData(string $moduleName, array $data): void
            {
                $this->moduleData[$moduleName] = $data;
            }
        };
    }
}
