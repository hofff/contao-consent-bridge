<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

use Hofff\Contao\Consent\Bridge\ConsentId;

abstract class AbstractServiceConsentId implements ConsentId
{
    /** @var string */
    protected static $category;

    /** @var string */
    private $service;

    public function __construct(string $serviceName)
    {
        $this->service = $serviceName;
    }

    public function equals(ConsentId $other) : bool
    {
        if ($other instanceof static) {
            return $this->service === $other->service;
        }

        return false;
    }

    public function toString() : string
    {
        return static::$category . ':' . $this->service;
    }

    public function jsonSerialize() : string
    {
        return $this->toString();
    }

    public function __toString() : string
    {
        return $this->toString();
    }
}
