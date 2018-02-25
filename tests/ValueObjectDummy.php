<?php declare(strict_types=1);

namespace Jorpo\ValueObject;

class ValueObjectDummy implements ValueObject
{
    private $value;

    public function __construct()
    {
        $this->value = uniqid();
    }

    /**
     * @inheritDoc
     */
    public function toPrimitive()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    function hash()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    function equals($obj): bool
    {
        return $this->hash() === $obj->hash();
    }
}
