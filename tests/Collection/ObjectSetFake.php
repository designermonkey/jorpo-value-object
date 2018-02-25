<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Collection;

use Jorpo\ValueObject\ValueObjectDummy;

class ObjectSetFake extends ObjectSet
{
    /**
     * @inheritDoc
     */
    public function objectType(): string
    {
        return ValueObjectDummy::class;
    }
}
