<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Render;

use Contao\Model;
use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Hofff\Contao\Consent\Bridge\Exception\RuntimeException;
use function ob_end_flush;
use function ob_start;

final class TemplateHelper
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    /** @var bool */
    private $block = false;

    public function __construct(ConsentToolManager $consentToolManager)
    {
        $this->consentToolManager = $consentToolManager;
    }

    public function requiresConsent(ConsentId $consentId) : bool
    {
        $consentTool = $this->consentToolManager->activeConsentTool();
        if ($consentTool === null) {
            return false;
        }

        return $consentTool->requiresConsent($consentId);
    }

    public function contentBlock(ConsentId $consentId, ?Model $model = null) : void
    {
        if (! $this->requiresConsent($consentId)) {
            return;
        }

        $consentTool = $this->consentToolManager->activeConsentTool();

        $this->block(
            static function (string $buffer) use ($consentId, $consentTool, $model) {
                return $consentTool->renderRaw($buffer, $consentId, $model);
            }
        );
    }

    public function placeholderBlock(ConsentId $consentId) : void
    {
        if (! $this->requiresConsent($consentId)) {
            return;
        }

        $consentTool = $this->consentToolManager->activeConsentTool();

        $this->block(
            static function (string $buffer) use ($consentId, $consentTool) {
                return $consentTool->renderPlaceholder($buffer, $consentId);
            }
        );
    }

    public function endBlock() : void
    {
        if (! $this->block) {
            throw new RuntimeException('Block rendering not started');
        }

        ob_end_flush();
    }

    private function block(callable $callback) : void
    {
        if ($this->block) {
            throw new RuntimeException('Block rendering already started');
        }

        $this->block = ob_start($callback);

        if (! $this->block) {
            throw new RuntimeException('Failed starting rendering block');
        }
    }
}
