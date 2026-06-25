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

namespace Xima\XimaTypo3InternalNews\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Xima\XimaTypo3InternalNews\Configuration;

/**
 * ConfigurationTest.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
final class ConfigurationTest extends TestCase
{
    #[Test]
    public function extensionKeyIsCorrect(): void
    {
        self::assertEquals('xima_typo3_internal_news', Configuration::EXT_KEY);
    }

    #[Test]
    public function extensionNameIsCorrect(): void
    {
        self::assertEquals('XimaTypo3InternalNews', Configuration::EXT_NAME);
    }

    #[Test]
    public function constantsAreFinal(): void
    {
        $reflection = new ReflectionClass(Configuration::class);

        $extKeyConstant = $reflection->getReflectionConstant('EXT_KEY');
        $extNameConstant = $reflection->getReflectionConstant('EXT_NAME');

        self::assertTrue($extKeyConstant->isFinal());
        self::assertTrue($extNameConstant->isFinal());
    }
}
