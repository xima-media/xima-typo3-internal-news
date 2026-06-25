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

namespace Xima\XimaTypo3InternalNews\Utilities;

use function in_array;
use function is_array;
use function is_object;


/**
 * BackendUserHelper.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */

class BackendUserHelper
{
    public function checkAndSetModuleDate(string $moduleName, mixed $value): bool
    {
        // Validate backend user exists and is authenticated
        if (!isset($GLOBALS['BE_USER']) || !is_object($GLOBALS['BE_USER'])) {
            return false;
        }

        $backendUser = $GLOBALS['BE_USER'];
        $return = true;

        $array = $backendUser->getModuleData($moduleName) ?? [];
        if (is_array($array) && in_array($value, $array, true)) {
            $return = false;
        }
        $array[] = $value;
        $backendUser->pushModuleData($moduleName, $array);

        return $return;
    }
}
