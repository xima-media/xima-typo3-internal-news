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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Controller;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Xima\XimaTypo3InternalNews\Controller\DateController;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;
use Xima\XimaTypo3InternalNews\Service\DateService;

final class DateControllerTest extends TestCase
{
    private DateController $subject;
    private NewsRepository $newsRepositoryMock;
    private ExtensionConfiguration $extensionConfigurationMock;
    private DateService $dateServiceMock;

    protected function setUp(): void
    {
        $this->newsRepositoryMock = $this->getMockBuilder(NewsRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->extensionConfigurationMock = $this->getMockBuilder(ExtensionConfiguration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dateServiceMock = $this->getMockBuilder(DateService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->extensionConfigurationMock
            ->method('get')
            ->willReturn([
                'enableNotifySessionHide' => true,
                'dateListCount' => 20,
            ]);

        $this->subject = new DateController(
            $this->newsRepositoryMock,
            $this->extensionConfigurationMock,
            $this->dateServiceMock
        );
    }

    #[Test]
    public function controllerCanBeInstantiated(): void
    {
        self::assertInstanceOf(DateController::class, $this->subject);
    }

    #[Test]
    public function controllerExtendsActionController(): void
    {
        $reflection = new \ReflectionClass(DateController::class);

        self::assertTrue($reflection->isSubclassOf(ActionController::class));
    }

    #[Test]
    public function controllerIsFinal(): void
    {
        $reflection = new \ReflectionClass(DateController::class);

        self::assertTrue($reflection->isFinal());
    }

    #[Test]
    public function controllerHasAsControllerAttribute(): void
    {
        $reflection = new \ReflectionClass(DateController::class);
        $attributes = $reflection->getAttributes();

        self::assertNotEmpty($attributes);

        // Check if AsController attribute exists
        $hasAsControllerAttribute = false;
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === 'TYPO3\CMS\Backend\Attribute\AsController') {
                $hasAsControllerAttribute = true;
                break;
            }
        }

        self::assertTrue($hasAsControllerAttribute);
    }

    #[Test]
    public function constructorAcceptsRequiredDependencies(): void
    {
        $reflection = new \ReflectionClass(DateController::class);
        $constructor = $reflection->getConstructor();

        self::assertNotNull($constructor);

        $parameters = $constructor->getParameters();
        self::assertCount(3, $parameters);

        // Check first parameter (NewsRepository)
        self::assertEquals('newsRepository', $parameters[0]->getName());
        $firstParamType = $parameters[0]->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $firstParamType);
        self::assertEquals(NewsRepository::class, $firstParamType->getName());

        // Check second parameter (ExtensionConfiguration)
        self::assertEquals('extensionConfiguration', $parameters[1]->getName());
        $secondParamType = $parameters[1]->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $secondParamType);
        self::assertEquals(ExtensionConfiguration::class, $secondParamType->getName());

        // Check third parameter (DateService)
        self::assertEquals('dateService', $parameters[2]->getName());
        $thirdParamType = $parameters[2]->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $thirdParamType);
        self::assertEquals('Xima\XimaTypo3InternalNews\Service\DateService', $thirdParamType->getName());
    }

    #[Test]
    public function notifiesActionMethodExists(): void
    {
        $reflection = new \ReflectionClass(DateController::class);

        self::assertTrue($reflection->hasMethod('notifiesAction'));

        $method = $reflection->getMethod('notifiesAction');
        self::assertTrue($method->isPublic());

        // Check return type
        $returnType = $method->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('Psr\Http\Message\ResponseInterface', $returnType->getName());
    }

    #[Test]
    public function newsActionMethodExists(): void
    {
        $reflection = new \ReflectionClass(DateController::class);

        self::assertTrue($reflection->hasMethod('newsAction'));

        $method = $reflection->getMethod('newsAction');
        self::assertTrue($method->isPublic());

        // Check return type
        $returnType = $method->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('Psr\Http\Message\ResponseInterface', $returnType->getName());
    }

    #[Test]
    public function controllerHasProtectedConfigurationProperty(): void
    {
        $reflection = new \ReflectionClass(DateController::class);

        self::assertTrue($reflection->hasProperty('configuration'));

        $property = $reflection->getProperty('configuration');
        self::assertTrue($property->isProtected());

        // Check property type
        $propertyType = $property->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $propertyType);
        self::assertEquals('array', $propertyType->getName());
    }

    #[Test]
    public function controllerUsesCorrectNamespace(): void
    {
        $reflection = new \ReflectionClass(DateController::class);

        self::assertEquals('Xima\XimaTypo3InternalNews\Controller', $reflection->getNamespaceName());
        self::assertEquals('DateController', $reflection->getShortName());
    }

    #[Test]
    public function controllerMethodsAreNotStatic(): void
    {
        $reflection = new \ReflectionClass(DateController::class);

        $notifiesAction = $reflection->getMethod('notifiesAction');
        $newsAction = $reflection->getMethod('newsAction');

        self::assertFalse($notifiesAction->isStatic());
        self::assertFalse($newsAction->isStatic());
    }

    #[Test]
    public function controllerActionMethodsHaveNoParameters(): void
    {
        $reflection = new \ReflectionClass(DateController::class);

        $notifiesAction = $reflection->getMethod('notifiesAction');
        $newsAction = $reflection->getMethod('newsAction');

        self::assertCount(0, $notifiesAction->getParameters());
        self::assertCount(0, $newsAction->getParameters());
    }
}
