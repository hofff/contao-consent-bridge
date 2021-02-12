<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;

interface ConsentId
{
    /**
     * Decides if serialized string representation is a valid representation of a consent id.
     */
    public static function supports(string $string): bool;

    /**
     * Create a consent id from serialized string representation.
     *
     * @throws InvalidArgumentException When Consent id could not be recreated.
     */
    public static function fromSerialized(string $string): self;

    /**
     * Compare it with another consent id.
     *
     * @param ConsentId $other Other consent id.
     */
    public function equals(ConsentId $other): bool;

    /**
     * Serialize the consent id as string which can be reconverted by fromSerialized().
     */
    public function serialize(): string;

    /**
     * Get a string representation of the consent id which is readable by the consent tool.
     */
    public function toString(): string;

    /**
     * Get a string representation of the consent id which is readable by the consent tool.
     */
    public function __toString(): string;
}
