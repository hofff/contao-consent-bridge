<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use function array_merge;
use function array_unique;

final class Bridge implements Plugin
{
    /**
     * @psalm-var class-string<ConsentId>
     * @var string[]
     */
    private $consentIds;

    /** @var string[] */
    private $elements;

    /** @var string[] */
    private $modules;

    public function load(Plugin $plugin) : void
    {
        $this->consentIds = array_unique(array_merge($plugin->providedConsentIds()));
        $this->elements   = array_unique(array_merge($plugin->supportedContentElements()));
        $this->modules    = array_unique(array_merge($plugin->supportedFrontendModules()));
    }

    /**
     * @psalm-return class-string<ConsentId>
     * @var string[]
     */
    public function providedConsentIds() : array
    {
        return $this->consentIds;
    }

    /** @var string[] */
    public function supportedContentElements() : array
    {
        return $this->elements;
    }

    /** @var string[] */
    public function supportedFrontendModules() : array
    {
        return $this->modules;
    }
}
