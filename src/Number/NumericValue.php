<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Number;

use Jorpo\ValueObject\ValueObject;

abstract class NumericValue implements ValueObject
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @inheritDoc
     */
    public function hash()
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    public function equals($obj): bool
    {
        return is_a($obj, get_called_class()) && $obj->hash() === $this->hash();
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
        return (string) $this->value;
    }
}
