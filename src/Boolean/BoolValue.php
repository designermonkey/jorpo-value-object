<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Boolean;

use ReflectionClass;
use Jorpo\ValueObject\ValueObject;
use Jorpo\ValueObject\Boolean\Exception\InvalidBoolValueException;
use Jorpo\ValueObject\Boolean\Exception\InvalidBoolValueFormatException;

class BoolValue implements ValueObject
{
    const TRUTHY = [1, '1', true, 'true', 'on', 'yes'];
    const FALSEY = [0, '0', false, 'false', 'off', 'no'];

    const FORMAT_INT = 0;
    const FORMAT_INTSTR = 1;
    const FORMAT_BOOL = 2;
    const FORMAT_BOOLSTR = 3;
    const FORMAT_ON_OFF = 4;
    const FORMAT_YES_NO = 5;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        if (null === $value || null === filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
            throw new InvalidBoolValueException;
        }

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
        return is_a($obj, BoolValue::class) && $obj->hash() === $this->hash();
    }

    /**
     * @inheritDoc
     */
    public function toPrimitive()
    {
        return filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Return a primitive boolean in a specified format
     *
     * @param int $format
     * @return void
     */
    public function toFormat(int $format = self::FORMAT_BOOL)
    {
        $reflection = new ReflectionClass(BoolValue::class);
        $constants = $reflection->getConstants();

        if (!in_array($format, $constants)) {
            throw new InvalidBoolValueFormatException;
        }

        if (false === filter_var($this->value, FILTER_VALIDATE_BOOLEAN)) {
            return self::FALSEY[$format];
        }

        return self::TRUTHY[$format];
    }

    public function invert(): BoolValue
    {
        return new self(!$this->toFormat());
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return filter_var($this->value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }
}
