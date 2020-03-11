<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

final class AnalyticsConsentId extends AbstractServiceConsentId
{
    /** @var string */
    protected static $category = 'analytics';

    public static function GOOGLE() : self
    {
        return new self('google');
    }

    public static function MATOMO() : self
    {
        return new self('matomo');
    }
}
