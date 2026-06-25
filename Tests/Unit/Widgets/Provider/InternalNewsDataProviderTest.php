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
use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use Xima\XimaTypo3InternalNews\Domain\Model\News;
use Xima\XimaTypo3InternalNews\Domain\Repository\NewsRepository;
use Xima\XimaTypo3InternalNews\Widgets\Provider\InternalNewsDataProvider;

/**
 * InternalNewsDataProviderTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class InternalNewsDataProviderTest extends TestCase
{
    #[Test]
    public function implementsListDataProviderInterface(): void
    {
        $repositoryMock = $this->createMock(NewsRepository::class);
        $provider = new InternalNewsDataProvider($repositoryMock);
        self::assertInstanceOf(ListDataProviderInterface::class, $provider);
    }

    #[Test]
    public function getItemsDelegatesToRepository(): void
    {
        $news1 = new News();
        $news1->setTitle('Test 1');
        $news2 = new News();
        $news2->setTitle('Test 2');
        $expected = [$news1, $news2];

        $repositoryMock = $this->createMock(NewsRepository::class);
        $repositoryMock->method('findAllByCurrentUser')->willReturn($expected);

        $provider = new InternalNewsDataProvider($repositoryMock);
        $result = $provider->getItems();

        self::assertSame($expected, $result);
    }

    #[Test]
    public function getItemsReturnsEmptyArrayWhenRepositoryReturnsEmpty(): void
    {
        $repositoryMock = $this->createMock(NewsRepository::class);
        $repositoryMock->method('findAllByCurrentUser')->willReturn([]);

        $provider = new InternalNewsDataProvider($repositoryMock);
        $result = $provider->getItems();

        self::assertSame([], $result);
    }
}
