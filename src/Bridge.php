<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\Exception\UnsupportedContentElement;
use Hofff\Contao\Consent\Bridge\Exception\UnsupportedFrontendModule;
use Hofff\Contao\Consent\Bridge\Render\RenderInformation;

use function array_keys;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
final class Bridge
{
    /** @var array<string, ConsentTool> */
    private array $consentTools = [];

    /**
     * @psalm-var list<class-string<ConsentId>>
     * @var string[]
     */
    private array $consentIds = [];

    /** @var array<string, non-empty-list<RenderInformation>> */
    private array $elements = [];

    /** @var array<string, non-empty-list<RenderInformation>> */
    private array $modules = [];

    /** @param Plugin[] $plugins */
    public function __construct(iterable $plugins)
    {
        foreach ($plugins as $plugin) {
            $plugin->load($this);
        }
    }

    public function registerConsentTool(ConsentTool $consentTool): self
    {
        $this->consentTools[$consentTool->name()] = $consentTool;

        return $this;
    }

    /** @psalm-param class-string<ConsentId> $consentIdClasses */
    public function registerConsentId(string ...$consentIdClasses): self
    {
        foreach ($consentIdClasses as $consentIdClass) {
            $this->consentIds[] = $consentIdClass;
        }

        return $this;
    }

    public function supportContentElement(string $type, RenderInformation $renderInformation): self
    {
        $this->elements[$type][] = $renderInformation;

        return $this;
    }

    public function supportFrontendModule(string $type, RenderInformation $renderInformation): self
    {
        $this->modules[$type][] = $renderInformation;

        return $this;
    }

    /** @return array<string, ConsentTool> */
    public function consentTools(): array
    {
        return $this->consentTools;
    }

    /**
     * @return string[]
     * @psalm-return list<class-string<ConsentId>>
     */
    public function providedConsentIds(): array
    {
        return $this->consentIds;
    }

    /**
     * @return string[]
     * @psalm-return list<string>
     */
    public function supportedContentElements(): array
    {
        return array_keys($this->elements);
    }

    /**
     * @return string[]
     * @psalm-return list<string>
     */
    public function supportedFrontendModules(): array
    {
        return array_keys($this->modules);
    }

    public function supportsContentElement(string $type): bool
    {
        return isset($this->elements[$type]);
    }

    public function contentElementRenderInformation(
        string $type,
        string|null $placeholderTemplate = null,
    ): RenderInformation {
        if (! isset($this->elements[$type])) {
            throw UnsupportedContentElement::ofType($type);
        }

        return $this->determineBestMatchingRenderInformation($this->elements[$type], $placeholderTemplate);
    }

    /** @return list<string> */
    public function contentElementPlaceholderTemplates(string $type): array
    {
        return $this->extractPlaceholderTemplates($this->elements[$type] ?? []);
    }

    public function supportsFrontendModule(string $type): bool
    {
        return isset($this->modules[$type]);
    }

    public function frontendModuleRenderInformation(
        string $type,
        string|null $placeholderTemplate = null,
    ): RenderInformation {
        if (! isset($this->modules[$type])) {
            throw UnsupportedFrontendModule::ofType($type);
        }

        return $this->determineBestMatchingRenderInformation($this->modules[$type], $placeholderTemplate);
    }

    /** @return list<string> */
    public function frontendModulePlaceholderTemplates(string $type): array
    {
        return $this->extractPlaceholderTemplates($this->modules[$type] ?? []);
    }

    /** @param non-empty-list<RenderInformation> $collection */
    private function determineBestMatchingRenderInformation(
        array $collection,
        string|null $placeholderTemplate = null,
    ): RenderInformation {
        foreach ($collection as $renderInformation) {
            if ($renderInformation->placeholderTemplate() !== $placeholderTemplate) {
                continue;
            }

            return $renderInformation;
        }

        return $collection[0];
    }

    /**
     * @param list<RenderInformation> $collection
     *
     * @return list<string>
     */
    private function extractPlaceholderTemplates(array $collection): array
    {
        $templates = [];

        foreach ($collection as $renderInformation) {
            $template = $renderInformation->placeholderTemplate();
            if ($template === null) {
                continue;
            }

            $templates[] = $template;
        }

        return $templates;
    }
}
