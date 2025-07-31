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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Utilities;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Xima\XimaTypo3InternalNews\Utilities\BackendUserHelper;

final class BackendUserHelperTest extends TestCase
{
    protected function setUp(): void
    {
        // Mock BE_USER global
        $GLOBALS['BE_USER'] = $this->createMockBackendUser();
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

        $result = BackendUserHelper::checkAndSetModuleDate($moduleName, $value);

        self::assertTrue($result);
    }

    #[Test]
    public function checkAndSetModuleDateReturnsFalseForExistingValue(): void
    {
        $moduleName = 'test_module';
        $value = 123;

        // First call should return true (new value)
        $firstResult = BackendUserHelper::checkAndSetModuleDate($moduleName, $value);
        self::assertTrue($firstResult);

        // Second call should return false (existing value)
        $secondResult = BackendUserHelper::checkAndSetModuleDate($moduleName, $value);
        self::assertFalse($secondResult);
    }

    #[Test]
    public function checkAndSetModuleDateHandlesStringValues(): void
    {
        $moduleName = 'test_module';
        $value = 'test_string';

        $result = BackendUserHelper::checkAndSetModuleDate($moduleName, $value);

        self::assertTrue($result);
    }

    #[Test]
    public function checkAndSetModuleDateHandlesDifferentModules(): void
    {
        $value = 123;

        $result1 = BackendUserHelper::checkAndSetModuleDate('module1', $value);
        $result2 = BackendUserHelper::checkAndSetModuleDate('module2', $value);

        // Same value in different modules should both return true
        self::assertTrue($result1);
        self::assertTrue($result2);
    }

    #[Test]
    public function checkAndSetModuleDateHandlesMultipleValuesInSameModule(): void
    {
        $moduleName = 'test_module';

        $result1 = BackendUserHelper::checkAndSetModuleDate($moduleName, 123);
        $result2 = BackendUserHelper::checkAndSetModuleDate($moduleName, 456);
        $result3 = BackendUserHelper::checkAndSetModuleDate($moduleName, 123); // Duplicate

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
        $result = BackendUserHelper::checkAndSetModuleDate($moduleName, $value);

        self::assertTrue($result);
    }

    #[Test]
    public function methodIsStatic(): void
    {
        $reflection = new \ReflectionMethod(BackendUserHelper::class, 'checkAndSetModuleDate');

        self::assertTrue($reflection->isStatic());
        self::assertTrue($reflection->isPublic());
    }

    #[Test]
    public function methodHasCorrectParameters(): void
    {
        $reflection = new \ReflectionMethod(BackendUserHelper::class, 'checkAndSetModuleDate');
        $parameters = $reflection->getParameters();

        self::assertCount(2, $parameters);
        self::assertEquals('moduleName', $parameters[0]->getName());
        self::assertEquals('value', $parameters[1]->getName());

        // Check parameter types
        $firstParamType = $parameters[0]->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $firstParamType);
        self::assertEquals('string', $firstParamType->getName());

        // Second parameter should be mixed type
        $secondParamType = $parameters[1]->getType();
        if ($secondParamType instanceof \ReflectionUnionType) {
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
        $reflection = new \ReflectionMethod(BackendUserHelper::class, 'checkAndSetModuleDate');
        $returnType = $reflection->getReturnType();

        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('bool', $returnType->getName());
    }

    private function createMockBackendUser(): object
    {
        return new class () {
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
