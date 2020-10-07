<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

final class AnalyticsConsentId extends ServiceConsentId
{
    /** @var string */
    protected static $category = 'analytics';

    /** @SuppressWarnings(PHPMD.CamelCaseMethodName) */
    public static function GOOGLE() : self
    {
        return new self('google');
    }

    /** @SuppressWarnings(PHPMD.CamelCaseMethodName) */
    public static function MATOMO() : self
    {
        return new self('matomo');
    }
}
