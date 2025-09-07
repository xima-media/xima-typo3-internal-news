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

namespace Xima\XimaTypo3InternalNews\Utilities;

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
