<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

final class WebfontsConsentId extends AbstractServiceConsentId
{
    /** @var string */
    protected static $category = 'webfonts';

    public static function GOOGLE() : self
    {
        return new self('google');
    }
}
