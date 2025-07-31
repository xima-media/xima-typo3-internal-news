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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Backend\ToolbarItems;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use Xima\XimaTypo3InternalNews\Backend\ToolbarItems\NewsItem;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;

final class NewsItemTest extends TestCase
{
    private NewsItem $subject;
    private NewsRepository $newsRepositoryMock;

    protected function setUp(): void
    {
        $this->newsRepositoryMock = $this->getMockBuilder(NewsRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new NewsItem($this->newsRepositoryMock);
    }

    #[Test]
    public function toolbarItemCanBeInstantiated(): void
    {
        self::assertInstanceOf(NewsItem::class, $this->subject);
    }

    #[Test]
    public function toolbarItemImplementsInterface(): void
    {
        $reflection = new \ReflectionClass(NewsItem::class);

        self::assertTrue($reflection->implementsInterface(ToolbarItemInterface::class));
    }

    #[Test]
    public function constructorAcceptsNewsRepository(): void
    {
        $reflection = new \ReflectionClass(NewsItem::class);
        $constructor = $reflection->getConstructor();

        self::assertNotNull($constructor);

        $parameters = $constructor->getParameters();
        self::assertCount(1, $parameters);

        self::assertEquals('newsRepository', $parameters[0]->getName());

        $paramType = $parameters[0]->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $paramType);
        self::assertEquals(NewsRepository::class, $paramType->getName());
    }

    #[Test]
    public function checkAccessReturnsTrue(): void
    {
        $result = $this->subject->checkAccess();

        self::assertTrue($result);
    }

    #[Test]
    public function hasDropDownReturnsTrue(): void
    {
        $result = $this->subject->hasDropDown();

        self::assertTrue($result);
    }

    #[Test]
    public function getIndexReturns50(): void
    {
        $result = $this->subject->getIndex();

        self::assertEquals(50, $result);
    }

    #[Test]
    public function getAdditionalAttributesReturnsEmptyArray(): void
    {
        $result = $this->subject->getAdditionalAttributes();

        self::assertEmpty($result);
    }

    #[Test]
    public function toolbarItemMethodsHaveCorrectReturnTypes(): void
    {
        $reflection = new \ReflectionClass(NewsItem::class);

        // checkAccess
        $checkAccess = $reflection->getMethod('checkAccess');
        $returnType = $checkAccess->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('bool', $returnType->getName());

        // getItem
        $getItem = $reflection->getMethod('getItem');
        $returnType = $getItem->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('string', $returnType->getName());

        // hasDropDown
        $hasDropDown = $reflection->getMethod('hasDropDown');
        $returnType = $hasDropDown->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('bool', $returnType->getName());

        // getDropDown
        $getDropDown = $reflection->getMethod('getDropDown');
        $returnType = $getDropDown->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('string', $returnType->getName());

        // getAdditionalAttributes
        $getAdditionalAttributes = $reflection->getMethod('getAdditionalAttributes');
        $returnType = $getAdditionalAttributes->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('array', $returnType->getName());

        // getIndex
        $getIndex = $reflection->getMethod('getIndex');
        $returnType = $getIndex->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('int', $returnType->getName());
    }

    #[Test]
    public function toolbarItemMethodsArePublic(): void
    {
        $reflection = new \ReflectionClass(NewsItem::class);

        $methods = [
            'checkAccess',
            'getItem',
            'hasDropDown',
            'getDropDown',
            'getAdditionalAttributes',
            'getIndex',
        ];

        foreach ($methods as $methodName) {
            $method = $reflection->getMethod($methodName);
            self::assertTrue($method->isPublic(), "Method {$methodName} should be public");
            self::assertFalse($method->isStatic(), "Method {$methodName} should not be static");
        }
    }

    #[Test]
    public function toolbarItemHasProtectedConfigurationProperty(): void
    {
        $reflection = new \ReflectionClass(NewsItem::class);

        self::assertTrue($reflection->hasProperty('configuration'));

        $property = $reflection->getProperty('configuration');
        self::assertTrue($property->isProtected());

        $propertyType = $property->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $propertyType);
        self::assertEquals('array', $propertyType->getName());
    }

    #[Test]
    public function toolbarItemUsesCorrectNamespace(): void
    {
        $reflection = new \ReflectionClass(NewsItem::class);

        self::assertEquals('Xima\XimaTypo3InternalNews\Backend\ToolbarItems', $reflection->getNamespaceName());
        self::assertEquals('NewsItem', $reflection->getShortName());
    }

    #[Test]
    public function toolbarItemMethodsHaveNoParameters(): void
    {
        $reflection = new \ReflectionClass(NewsItem::class);

        $parameterlessMethods = [
            'checkAccess',
            'getItem',
            'hasDropDown',
            'getDropDown',
            'getAdditionalAttributes',
            'getIndex',
        ];

        foreach ($parameterlessMethods as $methodName) {
            $method = $reflection->getMethod($methodName);
            self::assertCount(0, $method->getParameters(), "Method {$methodName} should have no parameters");
        }
    }
}
