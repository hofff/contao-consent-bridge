<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Dca;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DataContainer\PaletteNotFoundException;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;

use function count;

final class PageDcaListener
{
    /** @var ConsentToolManager */
    private $consentToolManager;

    public function __construct(ConsentToolManager $consentToolManager)
    {
        $this->consentToolManager = $consentToolManager;
    }

    public function onLoadDataContainer(string $name): void
    {
        static $processed = false;

        if ($processed || $name !== 'tl_page') {
            return;
        }

        $processed = true;

        if (count($this->consentToolManager->consentTools()) === 0) {
            return;
        }

        $manipulator = PaletteManipulator::create()
            ->addLegend(
                'hofff_consent_bridge_legend',
                'publish_legend',
                PaletteManipulator::POSITION_BEFORE,
            )
            ->addField(
                'hofff_consent_bridge_consent_tool',
                'hofff_consent_bridge_legend',
                PaletteManipulator::POSITION_APPEND,
            );

        foreach (['root', 'rootfallback'] as $palette) {
            try {
                $manipulator->applyToPalette($palette, 'tl_page');
            } catch (PaletteNotFoundException) {
                // Palette does not exist, ignore
            }
        }
    }

    /** @return string[] */
    public function consentToolOptions(): array
    {
        $options = [];

        foreach ($this->consentToolManager->consentTools() as $consentTool) {
            $options[] = $consentTool->name();
        }

        return $options;
    }
}
