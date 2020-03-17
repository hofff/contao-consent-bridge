<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ContentModel;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use function in_array;

final class RenderContentElementListener extends ConsentListener
{
    /** @var Bridge */
    private $bridge;

    /** @param string[] $elements */
    public function __construct(
        ConsentToolManager $consentToolManager,
        RequestScopeMatcher $scopeMatcher,
        Bridge $bridge
    ) {
        parent::__construct($consentToolManager, $scopeMatcher);

        $this->bridge = $bridge;
    }

    public function onGetContentElement(ContentModel $contentModel, string $buffer) : string
    {
        if (!in_array($contentModel->type, $this->bridge->supportedContentElements(), true)) {
            return $buffer;
        }

        return $this->render($buffer, (string) $contentModel->hofff_consent_bridge_tag);
    }
}
