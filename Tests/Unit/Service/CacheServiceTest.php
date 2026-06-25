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

namespace Xima\XimaTypo3InternalNews\Tests\Unit\Service;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use Xima\XimaTypo3InternalNews\Service\CacheService;


/**
 * CacheServiceTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

final class CacheServiceTest extends TestCase
{
    private CacheService $subject;
    private FrontendInterface $cacheMock;

    protected function setUp(): void
    {
        $this->cacheMock = $this->getMockBuilder(FrontendInterface::class)->getMock();
        $this->subject = new CacheService($this->cacheMock);

        // Mock global BE_USER for cache identifier generation
        $GLOBALS['BE_USER'] = $this->createMockBackendUser();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['BE_USER']);
    }

    #[Test]
    public function canBeInstantiated(): void
    {
        self::assertInstanceOf(CacheService::class, $this->subject);
    }

    #[Test]
    public function generateCacheIdentifierForAdminUser(): void
    {
        $GLOBALS['BE_USER'] = new class {
            public function isAdmin(): bool
            {
                return true;
            }
        };

        $result = $this->subject->generateCacheIdentifier([1, 2, 3]);

        self::assertEquals('xima_typo3_internal_news--all', $result);
    }

    #[Test]
    public function generateCacheIdentifierForNonAdminUser(): void
    {
        // BE_USER is already set to non-admin in setUp()
        $userGroups = [3, 1, 2]; // Will be sorted

        $result = $this->subject->generateCacheIdentifier($userGroups);

        self::assertEquals('xima_typo3_internal_news--1_2_3', $result);
    }

    #[Test]
    public function generateCacheIdentifierForEmptyUserGroups(): void
    {
        // BE_USER is already set to non-admin in setUp()

        $result = $this->subject->generateCacheIdentifier([]);

        self::assertEquals('xima_typo3_internal_news--', $result);
    }

    private function createMockBackendUser(): object
    {
        return new class {
            public function isAdmin(): bool
            {
                return false;
            }
        };
    }
}
