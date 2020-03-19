<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Manager;

use Contao\LayoutModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use function sprintf;

final class BridgeConsentToolManager implements ConsentToolManager
{
    /** @var ConsentTool|null */
    private $activeConsentTool;

    /** @var ConsentTool[] */
    private $consentTools = [];

    /** @return ConsentTool[] */
    public function consentTools() : array
    {
        return $this->consentTools;
    }

    public function register(ConsentTool $consentTool) : void
    {
        $this->consentTools[$consentTool->name()] = $consentTool;
    }

    public function has(string $name) : bool
    {
        return isset($this->consentTools[$name]);
    }

    public function get(string $name) : ConsentTool
    {
        if (!$this->has($name)) {
            throw new \InvalidArgumentException(sprintf('Consent tool "%s" is not known', $name));
        }

        return $this->consentTools[$name];
    }

    public function activate(
        string $name,
        PageModel $rootPageModel,
        ?PageModel $pageModel = null,
        ?LayoutModel $layoutModel = null
    ) : bool {
        if (!isset($this->consentTools[$name])) {
            throw new \InvalidArgumentException(sprintf('Consent tool "%s" is not known', $name));
        }

        if ($this->consentTools[$name]->activate($rootPageModel, $pageModel, $layoutModel)) {
            $this->activeConsentTool = $this->consentTools[$name];

            return true;
        }

        return false;
    }

    public function activeConsentTool() :? ConsentTool
    {
        return $this->activeConsentTool;
    }
}
