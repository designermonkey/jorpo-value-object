<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Number;

class FloatValue extends NumericValue
{
    /**
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }
}
