<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

interface ConsentId
{
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

    public function __toString() : string;
}