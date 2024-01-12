<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Exception;

use Throwable;

use function sprintf;

final class UnsupportedContentElement extends InvalidArgumentException
{
    public function __construct(
        private readonly string $type,
        string $message = '',
        int $code = 0,
        Throwable|null $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function ofType(string $type, int $code = 0, Throwable|null $previous = null): self
    {
        return new self($type, sprintf('Content element of type "%s" is not supported', $type), $code, $previous);
    }

    public function type(): string
    {
        return $this->type;
    }
}
