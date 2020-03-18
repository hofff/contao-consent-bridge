<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;

interface ConsentId
{
    /**
     * Decides if string representation is a valid representation of a consent id.
     */
    public static function supports(string $string) : bool;

    /**
     * Create a consent id from string representation.
     *
     * @throws InvalidArgumentException When Consent id could not be recreated
     */
    public static function fromString(string $string) : self;

    /**
     * Compare it with another consent id.
     *
     * @param ConsentId $other Other consent id.
     */
    public function equals(ConsentId $other) : bool;

    /**
     * Get a string representation of the consent id.
     */
    public function toString() : string;

    /**
     * Get string representation of the consent id.
     *
     * @return string
     */
    public function __toString() : string;
}
