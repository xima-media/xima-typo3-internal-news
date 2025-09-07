<?php

use Xima\XimaTypo3InternalNews\Hooks\DataHandler;

$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1726561044] = \Xima\XimaTypo3InternalNews\Backend\ToolbarItems\NewsItem::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xima_typo3_internal_news_cache'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['xima_typo3_internal_news_cache'] = DataHandler::class . '->clearCachePostProc';
