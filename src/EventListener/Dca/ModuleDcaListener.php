<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Dca;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;

final class ModuleDcaListener
{
    /** @var string[] */
    private $modules;

    /** @param string[] $modules */
    public function __construct(array $modules)
    {
        $this->modules = $modules;
    }

    public function initializePalettes(DataContainer $dataContainer) : void
    {
        $paletteManipulator = PaletteManipulator::create()
            ->addLegend('hofff_consent_bridge_legend', 'expert_legend')
            ->addField('hofff_consent_bridge_tag', 'hofff_consent_bridge_legend', PaletteManipulator::POSITION_APPEND);

        foreach ($this->modules as $module) {
            $paletteManipulator->applyToPalette($module, 'tl_module');
        }
    }
}
