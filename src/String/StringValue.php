<?php declare(strict_types=1);

namespace Jorpo\ValueObject\String;

use Jorpo\ValueObject\ValueObject;

class StringValue implements ValueObject
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

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
        return is_a($obj, StringValue::class) && $obj->hash() === $this->hash();
    }

    /**
     * @inheritDoc
     */
    public function toPrimitive()
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
