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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Widgets\Provider;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\{ButtonProviderInterface, ElementAttributesInterface};
use Xima\XimaTypo3InternalNews\Widgets\Provider\CreateInternalNewsButtonProvider;

/**
 * CreateInternalNewsButtonProviderTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class CreateInternalNewsButtonProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        GeneralUtility::purgeInstances();
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }

    #[Test]
    public function implementsButtonProviderInterface(): void
    {
        $provider = new CreateInternalNewsButtonProvider('Create');
        self::assertInstanceOf(ButtonProviderInterface::class, $provider);
    }

    #[Test]
    public function implementsElementAttributesInterface(): void
    {
        $provider = new CreateInternalNewsButtonProvider('Create');
        self::assertInstanceOf(ElementAttributesInterface::class, $provider);
    }

    #[Test]
    public function getTitleReturnsConfiguredTitle(): void
    {
        $provider = new CreateInternalNewsButtonProvider('Create New');
        self::assertSame('Create New', $provider->getTitle());
    }

    #[Test]
    public function getTargetReturnsEmptyStringByDefault(): void
    {
        $provider = new CreateInternalNewsButtonProvider('Create');
        self::assertSame('', $provider->getTarget());
    }

    #[Test]
    public function getTargetReturnsConfiguredTarget(): void
    {
        $provider = new CreateInternalNewsButtonProvider('Create', '_blank');
        self::assertSame('_blank', $provider->getTarget());
    }

    #[Test]
    public function getElementAttributesReturnsEmptyArray(): void
    {
        $provider = new CreateInternalNewsButtonProvider('Create');
        self::assertSame([], $provider->getElementAttributes());
    }

    #[Test]
    public function getLinkReturnsString(): void
    {
        $uriBuilderMock = $this->createMock(UriBuilder::class);
        $uriBuilderMock->method('buildUriFromRoute')->willReturn('/typo3/record/edit');
        GeneralUtility::setSingletonInstance(UriBuilder::class, $uriBuilderMock);

        $provider = new CreateInternalNewsButtonProvider('Create');
        $link = $provider->getLink();

        self::assertNotEmpty($link);
    }
}
