<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Collection;

use Jorpo\ValueObject\Identity\Uuid;

use PHPUnit\Framework\TestCase;

class UuidSetTest extends TestCase
{
    public function testShouldUseUuidAsObjectType()
    {
        $subject = new UuidSet;
        $this->assertSame(Uuid::class, $subject->objectType());
    }
}
