<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\ConsentId;

use Countable;
use Hofff\Contao\Consent\Bridge\ConsentId;
use IteratorAggregate;
use JsonSerializable;
use function array_map;
use function array_merge;
use function array_unique;
use function array_values;
use function count;
use function implode;

final class ConsentIds implements Countable, IteratorAggregate, JsonSerializable
{
    /** @var ConsentId[] */
    private $consentIds = [];

    /** @param ConsentId[] $consentIds */
    public static function fromArray(array $consentIds) : self
    {
        $collection = new self();

        foreach ($consentIds as $consentId) {
            $collection->add($consentId);
        }

        return $collection;
    }

    public static function fromList(ConsentId ...$consentIds) : self
    {
        return self::fromArray($consentIds);
    }

    private function add(ConsentId $consentId) : void
    {
        $this->consentIds[] = $consentId;
    }

    /**
     * @return ConsentId[]
     */
    public function toArray() : array
    {
        return $this->consentIds;
    }

    public function getIterator() : ConsentIdIterator
    {
        return new ConsentIdIterator($this);
    }

    public function count() : int
    {
        return count($this->consentIds);
    }

    public function isEmpty() : bool
    {
        return count($this->consentIds) === 0;
    }

    public function contains(ConsentId $consentId) : bool
    {
        foreach ($this->consentIds as $containedConsentId) {
            if ($containedConsentId->equals($consentId)) {
                return true;
            }
        }

        return false;
    }

    public function toString() : string
    {
        return implode(
            ',',
            array_map(
                static function (ConsentId $consentId) : string {
                    return $consentId->toString();
                },
                $this->consentIds
            )
        );
    }

    /** @return string[] */
    public function jsonSerialize() : array
    {
        return array_map(
            static function (ConsentId $consentId) : string {
                return $consentId->toString();
            },
            $this->consentIds
        );
    }

    public function merge(ConsentIds $consentIds) : ConsentIds
    {
        return self::fromArray(array_values(array_unique(array_merge($this->consentIds, $consentIds->toArray()))));
    }

    public function intersects(ConsentIds $consentIds) : bool
    {
        foreach ($consentIds as $consentId) {
            if ($this->contains($consentId)) {
                return true;
            }
        }

        return false;
    }
}
