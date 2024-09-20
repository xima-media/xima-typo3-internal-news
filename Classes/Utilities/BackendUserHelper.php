<?php

declare(strict_types=1);

namespace Xima\XimaTypo3InternalNews\Utilities;

class BackendUserHelper
{
    public static function checkAndSetModuleDate(string $moduleName, mixed $value): bool
    {
        $return = true;

        $array = $GLOBALS['BE_USER']->getModuleData($moduleName) ?: [];
        if (is_array($array) && in_array($value, $array)) {
            $return = false;
        }
        $array[] = $value;
        $GLOBALS['BE_USER']->pushModuleData($moduleName, $array);
        return $return;
    }
}
