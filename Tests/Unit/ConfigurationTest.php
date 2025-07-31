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

namespace Xima\XimaTypo3InternalNews\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Xima\XimaTypo3InternalNews\Configuration;

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
        $reflection = new \ReflectionClass(Configuration::class);
        
        $extKeyConstant = $reflection->getReflectionConstant('EXT_KEY');
        $extNameConstant = $reflection->getReflectionConstant('EXT_NAME');
        
        self::assertTrue($extKeyConstant->isFinal());
        self::assertTrue($extNameConstant->isFinal());
    }
}