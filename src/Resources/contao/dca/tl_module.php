<?php

declare(strict_types=1);

use Hofff\Contao\Consent\Bridge\EventListener\Dca\ConsentIdOptions;
use Hofff\Contao\Consent\Bridge\EventListener\Dca\ModuleDcaListener;

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = [ModuleDcaListener::class, 'initializePalettes'];
$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = [ModuleDcaListener::class, 'showConsentInfo'];


$GLOBALS['TL_DCA']['tl_module']['fields']['hofff_consent_bridge_tag'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_module']['hofff_consent_bridge_tag'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => [ConsentIdOptions::class, '__invoke'],
    'eval'             => [
        'tl_class'           => 'clr w50',
        'includeBlankOption' => true,
        'chosen'             => true,
        'multiple'           => false,
    ],
    'sql'              => ['type' => 'string', 'default' => null, 'notnull' => false],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['hofff_consent_bridge_placeholder_template'] = [
    'exclude'          => true,
    'inputType'        => 'select',
    'eval'             => [
        'tl_class'           => 'w50',
        'includeBlankOption' => true,
        'chosen'             => true,
        'multiple'           => false,
    ],
    'sql'              => ['type' => 'string', 'length' => 255, 'default' => null, 'notnull' => false],
];
