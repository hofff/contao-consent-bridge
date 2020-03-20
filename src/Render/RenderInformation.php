<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Render;

final class RenderInformation
{
    public const MODE_CUSTOM = 'custom';

    public const MODE_AUTO = 'auto';

    /**
     * @psalm-var self::MODE_CUSTOM | self::MODE_AUTO
     * @var string
     */
    private $mode;

    /** @var string|null */
    private $placeholderTemplate;

    /**
     * @psalm-param self::MODE_CUSTOM | self::MODE_AUTO $mode
     */
    private function __construct(string $mode, ?string $placeholderTemplate = null)
    {
        $this->mode                = $mode;
        $this->placeholderTemplate = $placeholderTemplate;
    }

    public static function customRender() : self
    {
        return new self(self::MODE_CUSTOM);
    }

    public static function autoRenderWithoutPlaceholder() : self
    {
        return new self(self::MODE_AUTO);
    }

    public static function autoRenderWithPlaceholder(string $placeholderTemplate) : self
    {
        return new self(self::MODE_AUTO, $placeholderTemplate);
    }

    public function isAutoRenderMode() : bool
    {
        return $this->mode === self::MODE_AUTO;
    }

    public function isCustomRenderMode() : bool
    {
        return $this->mode === self::MODE_CUSTOM;
    }

    public function supportsPlaceholder() : bool
    {
        return $this->placeholderTemplate !== null;
    }

    public function placeholderTemplate() : ?string
    {
        return $this->placeholderTemplate;
    }
}
