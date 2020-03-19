<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Dca;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;

final class ContentDcaListener
{
    /**
     * @var Bridge
     */
    private $bridge;

    /** @var ConsentToolManager  */
    private $consentToolManager;

    public function __construct(Bridge $bridge, ConsentToolManager $consentToolManager)
    {
        $this->bridge             = $bridge;
        $this->consentToolManager = $consentToolManager;
    }

    public function initializePalettes(DataContainer $dataContainer) : void
    {
        if (count($this->consentToolManager->consentTools()) === 0) {
            return;
        }

        $paletteManipulator = PaletteManipulator::create()
            ->addLegend('hofff_consent_bridge_legend', 'expert_legend')
            ->addField('hofff_consent_bridge_tag', 'hofff_consent_bridge_legend', PaletteManipulator::POSITION_APPEND);

        foreach ($this->bridge->supportedContentElements() as $element) {
            $paletteManipulator->applyToPalette($element, 'tl_content');
        }
    }
}
