<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Collection;

use Travreaable;
use IteratorAggregate;
use IteratorIterator;
use Ds\Collection;
use Ds\Set;
use Jorpo\ValueObject\Collection\Exception\InvalidObjectTypeException;

abstract class ObjectSet implements IteratorAggregate, Collection
{
    /**
     * @var Set
     */
    protected $set;

    /**
     * @param Set $set
     */
    final public function __construct(iterable $contents = [])
    {
        $set = new Set($contents);

        foreach ($set as $object) {
            $this->throwIfInvalidObjectType($object);
        }

        $this->set = $set;
    }

    /**
     * Define the specific object type this Set is restricted to
     *
     * @return string
     */
    abstract public function objectType(): string;

    /**
     * Return the internal Set instance
     *
     * @return Set
     */
    public function asSet(): Set
    {
        return $this->set;
    }

    /**
     * Adds zero or more values to the set.
     *
     * @param mixed ...$values
     */
    public function add(...$values)
    {
        foreach ($values as $value) {
            $this->throwIfInvalidObjectType($value);
            $this->set->add($value);
        }
    }

    /**
     * Returns the result of adding all given values to the set.
     *
     * @param iterable $values
     *
     * @return Set
     */
    public function merge(iterable $values): ObjectSet
    {
        $merged = clone $this;
        $merged->set = $this->set->copy();

        foreach ($values as $value) {
            if ($this->isValidObjectType($value)) {
                $merged->add($value);
            }
        }

        return $merged;
    }

    /**
     * Creates a new set that contains the values of this set as well as the
     * values of another set.
     *
     * Formally: A ∪ B = {x: x ∈ A ∨ x ∈ B}
     *
     * @param ObjectSet $set
     *
     * @return ObjectSet
     */
    public function union(ObjectSet $set): ObjectSet
    {
        $union = clone $this;
        $union->set = new Set;

        foreach ($this->set as $value) {
            $union->add($value);
        }

        foreach ($set as $value) {
            if ($this->isValidObjectType($value)) {
                $union->add($value);
            }
        }

        return $union;
    }

    /**
     * Creates a new set using values in either this set or in another set,
     * but not in both.
     *
     * Formally: A ⊖ B = {x : x ∈ (A \ B) ∪ (B \ A)}
     *
     * @param ObjectSet $set
     *
     * @return ObjectSet
     */
    public function xor(ObjectSet $set): ObjectSet
    {
        $merged = $this->merge($set);

        $merged->set = $merged->set->filter(function ($value) use ($set) {
            return $this->set->contains($value) ^ $set->asSet()->contains($value);
        });

        return $merged;
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->set->clear();
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->set);
    }

    /**
     * @inheritDoc
     */
    public function copy(): Collection
    {
        return new static($this->set->copy());
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return $this->set->isEmpty();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->set->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new IteratorIterator($this->set);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private function throwIfInvalidObjectType($object)
    {
        if (false === $this->isValidObjectType($object)) {
            throw new InvalidObjectTypeException;
        }
    }

    /**
     * @param mixed $object
     * @return void
     */
    private function isValidObjectType($object)
    {
        return is_a($object, $this->objectType());
    }
}
