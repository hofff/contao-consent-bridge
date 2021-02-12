<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

use Hofff\Contao\Consent\Bridge\ConsentId;
use InvalidArgumentException;

use function count;
use function explode;
use function sprintf;

abstract class ServiceConsentId implements ConsentId
{
    /** @var string */
    protected static $category;

    /** @var string */
    private $service;

    public function __construct(string $serviceName)
    {
        $this->service = $serviceName;
    }

    public static function supports(string $string): bool
    {
        try {
            self::extractServiceName($string);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    public static function fromSerialized(string $string): ConsentId
    {
        /** @psalm-suppress UnsafeInstantiation */
        return new static(self::extractServiceName($string));
    }

    public function equals(ConsentId $other): bool
    {
        if ($other instanceof static) {
            return $this->service === $other->service;
        }

        return false;
    }

    public function toString(): string
    {
        return static::$category . ':' . $this->service;
    }

    public function serialize(): string
    {
        return $this->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private static function extractServiceName(string $string): string
    {
        $parts = explode(':', $string, 2);

        if (count($parts) !== 2 || $parts[0] !== static::$category) {
            throw new InvalidArgumentException(
                sprintf('Given string "%s" is not a valid service consent tag', $string)
            );
        }

        return $parts[1];
    }
}
