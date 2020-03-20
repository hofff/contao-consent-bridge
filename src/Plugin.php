<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

interface Plugin
{
    public function load(Bridge $bridge) : void;
}
