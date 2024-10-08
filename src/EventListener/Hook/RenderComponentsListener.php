<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ContentModel;
use Contao\ModuleModel;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;

final class RenderComponentsListener extends ConsentListener
{
    public function __construct(
        ConsentToolManager $consentToolManager,
        RequestScopeMatcher $scopeMatcher,
        ConsentIdParser $consentIdParser,
        private readonly Bridge $bridge,
    ) {
        parent::__construct($consentToolManager, $scopeMatcher, $consentIdParser);
    }

    public function onGetContentElement(ContentModel $contentModel, string $buffer): string
    {
        $consentTool = $this->consentTool();
        if ($consentTool === null) {
            return $buffer;
        }

        if ($contentModel->type === 'module') {
            $moduleModel = ModuleModel::findByPk($contentModel->module);

            if ($moduleModel !== null) {
                return $this->onGetFrontendModule($moduleModel, $buffer);
            }

            return $buffer;
        }

        if (! $this->bridge->supportsContentElement($contentModel->type)) {
            return $buffer;
        }

        /** @psalm-suppress MixedArgument */
        $renderInformation = $this->bridge->contentElementRenderInformation(
            $contentModel->type,
            $contentModel->hofff_consent_bridge_placeholder_template,
        );

        return $this->renderContent(
            $buffer,
            // phpcs:disable
            (string) $contentModel->hofff_consent_bridge_tag,
            // phpcs:enable
            $renderInformation,
            $contentModel,
        );
    }

    public function onGetFrontendModule(ModuleModel $moduleModel, string $buffer): string
    {
        $consentTool = $this->consentTool();
        if ($consentTool === null) {
            return $buffer;
        }

        if (! $this->bridge->supportsFrontendModule($moduleModel->type)) {
            return $buffer;
        }

        /** @psalm-suppress MixedArgument */
        $renderInformation = $this->bridge->frontendModuleRenderInformation(
            $moduleModel->type,
            $moduleModel->hofff_consent_bridge_placeholder_template,
        );

        return $this->renderContent(
            $buffer,
            // phpcs:disable
            (string) $moduleModel->hofff_consent_bridge_tag,
            // phpcs:enable
            $renderInformation,
            $moduleModel,
        );
    }
}
