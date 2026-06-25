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
use Xima\XimaTypo3InternalNews\Widgets\Provider\ListInternalNewsButtonProvider;

/**
 * ListInternalNewsButtonProviderTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class ListInternalNewsButtonProviderTest extends TestCase
{
    protected function setUp(): void
    {
        GeneralUtility::purgeInstances();
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
    }

    #[Test]
    public function getTitleReturnsConfiguredTitle(): void
    {
        $provider = new ListInternalNewsButtonProvider('List All News');
        self::assertSame('List All News', $provider->getTitle());
    }

    #[Test]
    public function getTargetReturnsEmptyStringByDefault(): void
    {
        $provider = new ListInternalNewsButtonProvider('List');
        self::assertSame('', $provider->getTarget());
    }

    #[Test]
    public function getTargetReturnsConfiguredTarget(): void
    {
        $provider = new ListInternalNewsButtonProvider('List', '_blank');
        self::assertSame('_blank', $provider->getTarget());
    }

    #[Test]
    public function getElementAttributesReturnsEmptyArray(): void
    {
        $provider = new ListInternalNewsButtonProvider('List');
        self::assertSame([], $provider->getElementAttributes());
    }

    #[Test]
    public function getLinkReturnsString(): void
    {
        $uriBuilderMock = $this->createMock(UriBuilder::class);
        $uriBuilderMock->method('buildUriFromRoute')->willReturn('/typo3/module/web/list');
        GeneralUtility::setSingletonInstance(UriBuilder::class, $uriBuilderMock);

        $provider = new ListInternalNewsButtonProvider('List');
        $link = $provider->getLink();

        self::assertNotEmpty($link);
    }
}
