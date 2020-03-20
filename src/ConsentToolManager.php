<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Contao\LayoutModel;
use Contao\PageModel;
use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;

interface ConsentToolManager
{
    /** @return ConsentTool[] */
    public function consentTools() : array;

    public function has(string $name) : bool;

    /**
     * @throws InvalidArgumentException When no tool is found.
     */
    public function get(string $name) : ConsentTool;

    public function activate(
        string $name,
        PageModel $rootPageModel,
        ?PageModel $pageModel = null,
        ?LayoutModel $layoutModel = null
    ) : bool;

    public function activeConsentTool() : ?ConsentTool;
}
