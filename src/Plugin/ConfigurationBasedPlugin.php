<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Plugin;

use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\Exception\RuntimeException;
use Hofff\Contao\Consent\Bridge\Plugin;
use Hofff\Contao\Consent\Bridge\Render\RenderInformation;

/**
 * @psalm-type TComponentConfig = array{type: string, mode: 'auto'|'custom', placeholderTemplate?: string}
 */
final class ConfigurationBasedPlugin implements Plugin
{
    /**
     * @var mixed[]
     * @psalm-var array<string,TComponentConfig>
     */
    private $frontendModules;

    /**
     * @var mixed[]
     * @psalm-var array<string,TComponentConfig>
     */
    private $contentElements;

    /**
     * @param mixed[] $contentElements
     * @param mixed[] $frontendModules
     * @psalm-param array<string,TComponentConfig> $contentElements
     * @psalm-param array<string,TComponentConfig> $frontendModules
     */
    public function __construct(array $contentElements, array $frontendModules)
    {
        $this->contentElements = $contentElements;
        $this->frontendModules = $frontendModules;
    }

    public function load(Bridge $bridge): void
    {
        $this->registerContentElements($bridge);
        $this->registerFrontendModules($bridge);
    }

    private function registerContentElements(Bridge $bridge): void
    {
        foreach ($this->contentElements as $type => $config) {
            $bridge->supportContentElement($type, $this->createRenderInformation($config));
        }
    }

    private function registerFrontendModules(Bridge $bridge): void
    {
        foreach ($this->frontendModules as $type => $config) {
            $bridge->supportFrontendModule($type, $this->createRenderInformation($config));
        }
    }

    /** @psalm-param TComponentConfig $config */
    private function createRenderInformation(array $config): RenderInformation
    {
        switch ($config['mode']) {
            case 'auto':
                if (isset($config['placeholderTemplate'])) {
                    return RenderInformation::autoRenderWithPlaceholder($config['placeholderTemplate']);
                }

                return RenderInformation::autoRenderWithoutPlaceholder();

            case 'custom':
                return RenderInformation::customRender();

            default:
                throw new RuntimeException('Unsupported render information mode: ' . $config['mode']);
        }
    }
}
