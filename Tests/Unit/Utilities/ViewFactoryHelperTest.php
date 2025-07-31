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
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XimaTypo3InternalNews\Utilities\ViewFactoryHelper;

final class ViewFactoryHelperTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset GeneralUtility container for isolated tests
        GeneralUtility::purgeInstances();
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
    }

    #[Test]
    public function renderViewCanBeCalledWithMinimalArguments(): void
    {
        // We cannot easily test the actual rendering without a full TYPO3 setup,
        // but we can test that the method is properly typed and accessible
        $reflection = new \ReflectionMethod(ViewFactoryHelper::class, 'renderView');
        
        self::assertTrue($reflection->isStatic());
        self::assertTrue($reflection->isPublic());
        
        // Check parameter types
        $parameters = $reflection->getParameters();
        self::assertCount(3, $parameters);
        self::assertEquals('template', $parameters[0]->getName());
        self::assertEquals('values', $parameters[1]->getName());
        self::assertEquals('request', $parameters[2]->getName());
        self::assertTrue($parameters[2]->allowsNull());
    }

    #[Test]
    public function renderViewHasCorrectReturnType(): void
    {
        $reflection = new \ReflectionMethod(ViewFactoryHelper::class, 'renderView');
        $returnType = $reflection->getReturnType();
        
        self::assertNotNull($returnType);
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('string', $returnType->getName());
    }

    #[Test]
    public function privateMethodsExistForBothTypo3Versions(): void
    {
        $reflection = new \ReflectionClass(ViewFactoryHelper::class);
        
        $renderView12 = $reflection->getMethod('renderView12');
        $renderView13 = $reflection->getMethod('renderView13');
        
        self::assertTrue($renderView12->isPrivate());
        self::assertTrue($renderView12->isStatic());
        
        self::assertTrue($renderView13->isPrivate());
        self::assertTrue($renderView13->isStatic());
    }

    #[Test]
    public function templateParameterIsString(): void
    {
        $reflection = new \ReflectionMethod(ViewFactoryHelper::class, 'renderView');
        $templateParam = $reflection->getParameters()[0];
        $paramType = $templateParam->getType();
        
        self::assertTrue($templateParam->hasType());
        self::assertInstanceOf(\ReflectionNamedType::class, $paramType);
        self::assertEquals('string', $paramType->getName());
    }

    #[Test]
    public function valuesParameterIsArray(): void
    {
        $reflection = new \ReflectionMethod(ViewFactoryHelper::class, 'renderView');
        $valuesParam = $reflection->getParameters()[1];
        $paramType = $valuesParam->getType();
        
        self::assertTrue($valuesParam->hasType());
        self::assertInstanceOf(\ReflectionNamedType::class, $paramType);
        self::assertEquals('array', $paramType->getName());
    }

    #[Test]
    public function requestParameterIsNullableServerRequestInterface(): void
    {
        $reflection = new \ReflectionMethod(ViewFactoryHelper::class, 'renderView');
        $requestParam = $reflection->getParameters()[2];
        $paramType = $requestParam->getType();
        
        self::assertTrue($requestParam->hasType());
        self::assertTrue($requestParam->allowsNull());
        self::assertInstanceOf(\ReflectionNamedType::class, $paramType);
        self::assertEquals(ServerRequestInterface::class, $paramType->getName());
    }
}