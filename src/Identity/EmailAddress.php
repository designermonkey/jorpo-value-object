<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Identity;

use Jorpo\ValueObject\String\StringValue;
use Jorpo\ValueObject\Identity\Exception\InvalidEmailAddressException;

class EmailAddress extends StringValue
{
    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException(sprintf(
                "The string '%s' is not a valid email address",
                $value
            ));
        }

        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function equals($obj): bool
    {
        return is_a($obj, EmailAddress::class) && $obj->hash() === $this->hash();
    }
}
