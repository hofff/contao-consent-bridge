<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\Exception\UnsupportedContentElement;
use Hofff\Contao\Consent\Bridge\Exception\UnsupportedFrontendModule;
use Hofff\Contao\Consent\Bridge\Render\RenderInformation;
use function array_keys;

final class Bridge
{
    /**
     * @var array<string, ConsentTool>
     */
    private $consentTools = [];

    /**
     * @psalm-var list<class-string<ConsentId>>
     * @var string[]
     */
    private $consentIds = [];

    /**
     * @var array<string, RenderInformation>
     */
    private $elements = [];

    /**
     * @var array<string, RenderInformation>
     */
    private $modules = [];

    public function registerConsentTool(ConsentTool $consentTool) : self
    {
        $this->consentTools[$consentTool->name()] = $consentTool;

        return $this;
    }

    /**
     * @psalm-param class-string<ConsentId> $consentIdClasses
     */
    public function registerConsentId(string ...$consentIdClasses) : self
    {
        foreach ($consentIdClasses as $consentIdClass) {
            $this->consentIds[] = $consentIdClass;
        }

        return $this;
    }

    public function supportContentElement(string $type, RenderInformation $renderInformation) : self
    {
        $this->elements[$type] = $renderInformation;

        return $this;
    }

    public function supportFrontendModule(string $type, RenderInformation $renderInformation) : self
    {
        $this->modules[$type] = $renderInformation;

        return $this;
    }

    /**
     * @return array<string, ConsentTool>
     */
    public function consentTools() : array
    {
        return $this->consentTools;
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
        return array_keys($this->elements);
    }

    /** @psalm-return list<string> */
    public function supportedFrontendModules() : array
    {
        return array_keys($this->modules);
    }

    public function supportsContentElement(string $type) : bool
    {
        return isset($this->elements[$type]);
    }

    public function contentElementRenderInformation(string $type) : RenderInformation
    {
        if (!isset($this->elements[$type])) {
            throw UnsupportedContentElement::ofType($type);
        }

        return $this->elements[$type];
    }

    public function supportsFrontendModule(string $type) : bool
    {
        return isset($this->modules[$type]);
    }

    public function frontendModuleRenderInformation(string $type) : RenderInformation
    {
        if (!isset($this->modules[$type])) {
            throw UnsupportedFrontendModule::ofType($type);
        }

        return $this->modules[$type];
    }
}
