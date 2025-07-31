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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Widgets;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\JavaScriptInterface;
use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use Xima\XimaTypo3InternalNews\Widgets\InternalNewsWidget;

final class InternalNewsWidgetTest extends TestCase
{
    private InternalNewsWidget $subject;
    private WidgetConfigurationInterface $configurationMock;
    private ListDataProviderInterface $dataProviderMock;
    private ButtonProviderInterface $buttonProviderMock;

    protected function setUp(): void
    {
        $this->configurationMock = $this->getMockBuilder(WidgetConfigurationInterface::class)->getMock();
        $this->dataProviderMock = $this->getMockBuilder(ListDataProviderInterface::class)->getMock();
        $this->buttonProviderMock = $this->getMockBuilder(ButtonProviderInterface::class)->getMock();
        
        $this->subject = new InternalNewsWidget(
            $this->configurationMock,
            $this->dataProviderMock,
            $this->buttonProviderMock,
            ['button1' => 'test'],
            ['option1' => 'value1']
        );
        
        // Mock BE_USER global for widget rendering
        $GLOBALS['BE_USER'] = $this->createMockBackendUser();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['BE_USER']);
    }

    #[Test]
    public function widgetCanBeInstantiated(): void
    {
        self::assertInstanceOf(InternalNewsWidget::class, $this->subject);
    }

    #[Test]
    public function widgetImplementsRequiredInterfaces(): void
    {
        $reflection = new \ReflectionClass(InternalNewsWidget::class);
        
        self::assertTrue($reflection->implementsInterface(WidgetInterface::class));
        self::assertTrue($reflection->implementsInterface(AdditionalCssInterface::class));
        self::assertTrue($reflection->implementsInterface(JavaScriptInterface::class));
    }

    #[Test]
    public function constructorAcceptsAllParameters(): void
    {
        $reflection = new \ReflectionClass(InternalNewsWidget::class);
        $constructor = $reflection->getConstructor();
        
        self::assertNotNull($constructor);
        
        $parameters = $constructor->getParameters();
        self::assertCount(5, $parameters);
        
        // Check parameter names and types
        self::assertEquals('configuration', $parameters[0]->getName());
        self::assertEquals('dataProvider', $parameters[1]->getName());
        self::assertEquals('buttonProvider', $parameters[2]->getName());
        self::assertEquals('buttons', $parameters[3]->getName());
        self::assertEquals('options', $parameters[4]->getName());
        
        // Check nullable parameters
        self::assertTrue($parameters[2]->allowsNull()); // buttonProvider
        self::assertFalse($parameters[3]->allowsNull()); // buttons
        self::assertFalse($parameters[4]->allowsNull()); // options
    }

    #[Test]
    public function renderWidgetContentMethodExists(): void
    {
        $reflection = new \ReflectionClass(InternalNewsWidget::class);
        
        self::assertTrue($reflection->hasMethod('renderWidgetContent'));
        
        $method = $reflection->getMethod('renderWidgetContent');
        self::assertTrue($method->isPublic());
        
        $returnType = $method->getReturnType();
        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertEquals('string', $returnType->getName());
    }

    #[Test]
    public function getOptionsReturnsConfiguredOptions(): void
    {
        $options = $this->subject->getOptions();
        
        self::assertIsArray($options);
        self::assertEquals(['option1' => 'value1'], $options);
    }

    #[Test]
    public function getCssFilesReturnsExpectedFiles(): void
    {
        $cssFiles = $this->subject->getCssFiles();
        
        self::assertIsArray($cssFiles);
        self::assertCount(1, $cssFiles);
        self::assertEquals('EXT:xima_typo3_internal_news/Resources/Public/Stylesheets/Backend.css', $cssFiles[0]);
    }

    #[Test]
    public function getJavaScriptModuleInstructionsReturnsExpectedModules(): void
    {
        $jsInstructions = $this->subject->getJavaScriptModuleInstructions();
        
        self::assertIsArray($jsInstructions);
        self::assertCount(1, $jsInstructions);
        
        // Check that it's a JavaScriptModuleInstruction
        self::assertInstanceOf('TYPO3\CMS\Core\Page\JavaScriptModuleInstruction', $jsInstructions[0]);
    }

    #[Test]
    public function widgetUsesCorrectNamespace(): void
    {
        $reflection = new \ReflectionClass(InternalNewsWidget::class);
        
        self::assertEquals('Xima\XimaTypo3InternalNews\Widgets', $reflection->getNamespaceName());
        self::assertEquals('InternalNewsWidget', $reflection->getShortName());
    }

    #[Test]
    public function widgetHasProtectedServerRequestProperty(): void
    {
        $reflection = new \ReflectionClass(InternalNewsWidget::class);
        
        self::assertTrue($reflection->hasProperty('request'));
        
        $property = $reflection->getProperty('request');
        self::assertTrue($property->isProtected());
    }

    #[Test]
    public function constructorParametersHaveCorrectVisibility(): void
    {
        $reflection = new \ReflectionClass(InternalNewsWidget::class);
        
        // Check that promoted properties are protected/readonly
        self::assertTrue($reflection->hasProperty('configuration'));
        self::assertTrue($reflection->hasProperty('dataProvider'));
        self::assertTrue($reflection->hasProperty('buttonProvider'));
        self::assertTrue($reflection->hasProperty('buttons'));
        self::assertTrue($reflection->hasProperty('options'));
        
        $configuration = $reflection->getProperty('configuration');
        $dataProvider = $reflection->getProperty('dataProvider');
        $buttonProvider = $reflection->getProperty('buttonProvider');
        $buttons = $reflection->getProperty('buttons');
        $options = $reflection->getProperty('options');
        
        self::assertTrue($configuration->isProtected());
        self::assertTrue($dataProvider->isProtected());
        self::assertTrue($buttonProvider->isProtected());
        self::assertTrue($buttons->isProtected());
        self::assertTrue($options->isProtected());
    }

    #[Test]
    public function widgetMethodsAreNotStatic(): void
    {
        $reflection = new \ReflectionClass(InternalNewsWidget::class);
        
        $methods = ['renderWidgetContent', 'getOptions', 'getCssFiles', 'getJavaScriptModuleInstructions'];
        
        foreach ($methods as $methodName) {
            $method = $reflection->getMethod($methodName);
            self::assertFalse($method->isStatic(), "Method {$methodName} should not be static");
        }
    }

    private function createMockBackendUser(): object
    {
        return new class {
            public function check(string $table, string $action): bool
            {
                return true; // Always allow for testing
            }
        };
    }
}