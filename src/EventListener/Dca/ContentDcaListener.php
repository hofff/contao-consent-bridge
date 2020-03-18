<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Dca;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;
use Hofff\Contao\Consent\Bridge\Bridge;

final class ContentDcaListener
{
    /**
     * @var Bridge
     */
    private $bridge;

    /** @param string[] $elements */
    public function __construct(Bridge $bridge)
    {
        $this->bridge = $bridge;
    }

    public function initializePalettes(DataContainer $dataContainer) : void
    {
        $paletteManipulator = PaletteManipulator::create()
            ->addLegend('hofff_consent_bridge_legend', 'expert_legend')
            ->addField('hofff_consent_bridge_tag', 'hofff_consent_bridge_legend', PaletteManipulator::POSITION_APPEND);

        foreach ($this->bridge->supportedContentElements() as $element) {
            $paletteManipulator->applyToPalette($element, 'tl_content');
        }
    }
}
