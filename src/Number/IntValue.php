<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Number;

class IntValue extends NumericValue
{
    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
