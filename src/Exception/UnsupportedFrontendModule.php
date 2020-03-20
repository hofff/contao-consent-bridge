<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Exception;

use Throwable;
use function sprintf;

final class UnsupportedFrontendModule extends InvalidArgumentException
{
    /** @var string */
    private $type;

    public function __construct(string $type, string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->type = $type;
    }

    public static function ofType(string $type, int $code = 0, Throwable $previous = null) : self
    {
        return new self($type, sprintf('Content element of type "%s" is not supported', $type), $code, $previous);
    }

    public function type() : string
    {
        return $this->type;
    }
}
