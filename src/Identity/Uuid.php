<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Identity;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Jorpo\ValueObject\ValueObject;
use Jorpo\ValueObject\Identity\Exception\InvalidUuidException;

class Uuid implements ValueObject
{
    const UUID_PATTERN = '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[89abAB][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}$/';
    const NIL = '00000000-0000-0000-0000-000000000000';

    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Generate a Uuid unique in time and space (version 1)
     *
     * @return Uuid
     */
    public static function generate(): Uuid
    {
         return new static((string) RamseyUuid::uuid1());
    }

    /**
     * Generate a random Uuid (version 4)
     *
     * @return Uuid
     */
    public static function random(): Uuid
    {
        return new static((string) RamseyUuid::uuid4());
    }

    /**
     * Make a valid version 5 uuid string into an Identifier instance
     *
     * @param  string $string
     * @return Identifier
     * @throws InvalidIdentifierException
     */
    public static function fromString(string $string): Uuid
    {
        if (!preg_match(self::UUID_PATTERN, $string)) {
            throw new InvalidUuidException(sprintf(
                "Value '%s' is not a valid UUID.",
                $string
            ));
        }

        return new static($string);
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
        return is_a($obj, Uuid::class) && $obj->hash() === $this->hash();
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
