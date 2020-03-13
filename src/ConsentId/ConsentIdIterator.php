<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

use Hofff\Contao\Consent\Bridge\ConsentId;
use Iterator;
use function count;
use function iterator_count;

final class ConsentIdIterator implements Iterator
{
    /** @var ConsentId[] */
    private $consentIds;

    /** @var int */
    private $position;

    public function __construct(ConsentIds $consentIds)
    {
        $this->consentIds = $consentIds->toArray();
        $this->position   = 0;
    }

    public function count() : int
    {
        return iterator_count($this);
    }

    public function rewind() : void
    {
        $this->position = 0;
    }

    public function valid() : bool
    {
        return $this->position < count($this->consentIds);
    }

    public function key() : int
    {
        return $this->position;
    }

    public function current() : ConsentId
    {
        return $this->consentIds[$this->position];
    }

    public function next() : void
    {
        $this->position++;
    }
}
