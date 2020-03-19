<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ContentModel;
use Contao\ModuleModel;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentId\ConsentIdParser;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use function in_array;

final class RenderComponentsListener extends ConsentListener
{
    /** @var Bridge */
    private $bridge;

    /** @param string[] $elements */
    public function __construct(
        ConsentToolManager $consentToolManager,
        RequestScopeMatcher $scopeMatcher,
        ConsentIdParser $consentIdParser,
        Bridge $bridge
    ) {
        parent::__construct($consentToolManager, $scopeMatcher, $consentIdParser);

        $this->bridge = $bridge;
    }

    public function onGetContentElement(ContentModel $contentModel, string $buffer) : string
    {
        if (!in_array($contentModel->type, $this->bridge->supportedContentElements(), true)) {
            return $buffer;
        }

        if ($contentModel->type === 'module') {
            $moduleModel = ModuleModel::findByPk($contentModel->module);

            if ($moduleModel !== null) {
                return $this->onGetFrontendModule($moduleModel, $buffer);
            }

            return $buffer;
        }

        return $this->renderContent($buffer, (string) $contentModel->hofff_consent_bridge_tag, $contentModel);
    }

    public function onGetFrontendModule(ModuleModel $moduleModel, string $buffer) : string
    {
        if (!in_array($moduleModel->type, $this->bridge->supportedFrontendModules(), true)) {
            return $buffer;
        }

        return $this->renderContent($buffer, (string) $moduleModel->hofff_consent_bridge_tag, $moduleModel);
    }
}
