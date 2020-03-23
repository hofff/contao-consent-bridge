<?php

declare(strict_types=1);

use Hofff\Contao\Consent\Bridge\EventListener\Dca\PageDcaListener;

$GLOBALS['TL_DCA']['tl_page']['fields']['hofff_consent_bridge_consent_tool'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_page']['hofff_consent_bridge_consent_tool'],
    'inputType'        => 'select',
    'options_callback' => [PageDcaListener::class, 'consentToolOptions'],
    'reference'        => &$GLOBALS['TL_LANG']['tl_page']['hofff_consent_bridge_consent_tools'],
    'eval'             => [
        'includeBlankOption' => true,
        'chosen'             => true,
        'submitOnChange'     => true,
        'helpwizard'         => true,
        'tl_class'           => 'w50',
    ],
    'sql'              => [
        'type'    => 'string',
        'notnull' => true,
        'default' => '',
    ],
];
