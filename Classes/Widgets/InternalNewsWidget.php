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

namespace Xima\XimaTypo3InternalNews\Widgets;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\{AdditionalCssInterface, ButtonProviderInterface, JavaScriptInterface, ListDataProviderInterface, WidgetConfigurationInterface, WidgetInterface};
use Xima\XimaTypo3InternalNews\Configuration;
use Xima\XimaTypo3InternalNews\Utilities\ViewFactoryHelper;

/**
 * InternalNewsWidget.
 *
 * @author Konrad Michalik <hej@konradmichalik.dev>
 * @license GPL-2.0-or-later
 */
class InternalNewsWidget implements WidgetInterface, AdditionalCssInterface, JavaScriptInterface
{
    protected ServerRequestInterface $request;

    public function __construct(
        protected readonly WidgetConfigurationInterface $configuration,
        protected readonly ListDataProviderInterface $dataProvider,
        protected readonly ?ButtonProviderInterface $buttonProvider = null,
        protected readonly array $buttons = [],
        protected array $options = [],
    ) {}

    public function renderWidgetContent(): string
    {
        return ViewFactoryHelper::renderView(
            'Backend/Widgets/List.html',
            [
                'configuration' => $this->configuration,
                'records' => $this->dataProvider->getItems(),
                'buttons' => $GLOBALS['BE_USER']->check('tables_modify', 'tx_ximatypo3internalnews_domain_model_news') ? $this->buttons : null,
                'options' => $this->options,
                'version' => GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion(),
            ],
        );
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getCssFiles(): array
    {
        return ['EXT:'.Configuration::EXT_KEY.'/Resources/Public/Stylesheets/Backend.css'];
    }

    public function getJavaScriptModuleInstructions(): array
    {
        return [
            JavaScriptModuleInstruction::create('@xima/ximatypo3internalnews/widget.js'),
        ];
    }
}
