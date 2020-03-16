<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Dca;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;

final class ContentDcaListener
{
    /** @var string[] */
    private $elements;

    /** @param string[] $elements */
    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public function initializePalettes(DataContainer $dataContainer) : void
    {
        $paletteManipulator = PaletteManipulator::create()
            ->addLegend('hofff_consent_bridge_legend', 'expert_legend')
            ->addField('hofff_consent_bridge_tag', 'hofff_consent_bridge_legend', PaletteManipulator::POSITION_APPEND);

        foreach ($this->elements as $element) {
            $paletteManipulator->applyToPalette($element, 'tl_content');
        }
    }
}
