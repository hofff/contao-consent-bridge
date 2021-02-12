<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

use Hofff\Contao\Consent\Bridge\ConsentId;
use Hofff\Contao\Consent\Bridge\Exception\InvalidArgumentException;

interface ConsentIdParser
{
    /**
     * @throws InvalidArgumentException When an invalid consent id is given.
     */
    public function parse(string $string): ConsentId;
}
