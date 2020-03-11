<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

use Hofff\Contao\Consent\Bridge\ConsentId;

/**
 * Class ServiceConsentId identifies a specific service.
 */
final class TagConsentId implements ConsentId
{
    /**
     * Id of the service.
     *
     * @var int
     */
    private $serviceId;

    /**
     * @param int $serviceId Id of the service.
     */
    public function __construct(int $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function equals(ConsentId $other) : bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $other->serviceId === $this->serviceId;
    }

    public function toString() : string
    {
        return 'tag:' . $this->serviceId;
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
