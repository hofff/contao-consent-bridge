<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentId;
use InvalidArgumentException;

final class BridgeConfiguredConsentIdParser implements ConsentIdParser
{
    /** @var Bridge */
    private $bridge;

    public function __construct(Bridge $bridge)
    {
        $this->bridge = $bridge;
    }

    public function parse(string $string) : ConsentId
    {
        foreach ($this->bridge->providedConsentIds() as $class) {
            if ($class::supports($string)) {
                return $class::fromString($string);
            }
        }

        throw new InvalidArgumentException(sprintf('No matching ConsentId found for value "%s"', $string));
    }
}
