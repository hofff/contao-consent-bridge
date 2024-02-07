<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Manager;

use Contao\LayoutModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentTool;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use InvalidArgumentException;

use function sprintf;

final class BridgeConsentToolManager implements ConsentToolManager
{
    private ConsentTool|null $activeConsentTool = null;

    public function __construct(private readonly Bridge $bridge)
    {
    }

    /** @return ConsentTool[] */
    public function consentTools(): array
    {
        return $this->bridge->consentTools();
    }

    public function has(string $name): bool
    {
        return isset($this->consentTools()[$name]);
    }

    public function get(string $name): ConsentTool
    {
        if (! $this->has($name)) {
            throw new InvalidArgumentException(sprintf('Consent tool "%s" is not known', $name));
        }

        return $this->consentTools()[$name];
    }

    public function activate(
        string $name,
        PageModel $rootPageModel,
        PageModel|null $pageModel = null,
        LayoutModel|null $layoutModel = null,
    ): bool {
        $consentTool = $this->get($name);

        if ($consentTool->activate($rootPageModel, $pageModel, $layoutModel)) {
            $this->activeConsentTool = $consentTool;

            return true;
        }

        return false;
    }

    public function activeConsentTool(): ConsentTool|null
    {
        return $this->activeConsentTool;
    }
}
