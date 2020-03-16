<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Hook;

use Contao\ContentModel;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use function in_array;

final class RenderContentElementListener extends ConsentListener
{
    /** @var string[] */
    private $elements;

    /** @param string[] $elements */
    public function __construct(
        ConsentToolManager $consentToolManager,
        RequestScopeMatcher $scopeMatcher,
        array $elements
    ) {
        parent::__construct($consentToolManager, $scopeMatcher);

        $this->elements = $elements;
    }

    public function onGetContentElement(ContentModel $contentModel, string $buffer) : string
    {
        if (!in_array($contentModel->type, $this->elements, true)) {
            return $buffer;
        }

        return $this->render($buffer, (string) $contentModel->hofff_consent_bridge_tag);
    }
}
