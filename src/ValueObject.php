<?php declare(strict_types=1);

namespace Jorpo\ValueObject;

use Ds\Hashable;

interface ValueObject extends Hashable
{
    /**
     * Coerce a value object into a primitive type
     *
     * @return mixed
     */
    public function toPrimitive();

    /**
     * Coerce a value object into a string type
     *
     * @return string
     */
    public function __toString(): string;
}
