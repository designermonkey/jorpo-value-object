<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Collection;

use Jorpo\ValueObject\Identity\Uuid;

class UuidSet extends ObjectSet
{
    /**
     * Define the specific object type this Set is restricted to
     *
     * @return string
     */
    public function objectType(): string
    {
        return Uuid::class;
    }
}
