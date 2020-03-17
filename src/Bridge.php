<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\ConsentId;
use function array_merge;
use function array_unique;
use function array_values;

final class Bridge implements Plugin
{
    /**
     * @psalm-var list<class-string<ConsentId>>
     * @var string[]
     */
    private $consentIds = [];

    /** @psalm-var list<string> */
    private $elements = [];

    /** @psalm-var list<string> */
    private $modules = [];

    public function load(Plugin $plugin) : void
    {
        $this->consentIds = array_values(array_unique(array_merge($this->consentIds, $plugin->providedConsentIds())));
        $this->elements   = array_values(array_unique(array_merge($this->elements, $plugin->supportedContentElements())));
        $this->modules    = array_values(array_unique(array_merge($this->modules, $plugin->supportedFrontendModules())));
    }

    /**
     * @psalm-return list<class-string<ConsentId>>
     * @var string[]
     */
    public function providedConsentIds() : array
    {
        return $this->consentIds;
    }

    /** @psalm-return list<string> */
    public function supportedContentElements() : array
    {
        return $this->elements;
    }

    /** @psalm-return list<string> */
    public function supportedFrontendModules() : array
    {
        return $this->modules;
    }
}
