<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Identity;

use Throwable;
use PHPUnit\Framework\TestCase;
use Jorpo\ValueObject\ValueObject;
use Jorpo\ValueObject\Identity\Exception\InvalidUuidException;

class UuidTest extends TestCase
{
    const UUID_ONE = 'ec18462f-ae6c-4d50-a025-97f106ef9ece';

    public function testShouldNotBeNewable()
    {
        try {
            $subject = new Uuid;
        } catch (Throwable $error) {
            // var_dump($error);die;
            $this->assertSame(0, strpos($error->getMessage(), 'Call to private Jorpo\ValueObject\Identity\Uuid::__construct()'));
        }
    }

    public function testShouldGenerateuuid()
    {
        $uuid = Uuid::generate();
        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertSame(1, preg_match(Uuid::UUID_PATTERN, (string) $uuid));
    }

    public function testShouldGenerateRandomUuid()
    {
        $uuid = Uuid::random();
        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertSame(1, preg_match(Uuid::UUID_PATTERN, (string) $uuid));
    }

    public function testShouldAcceptExistingStringUuid()
    {
        $uuid = Uuid::fromString(self::UUID_ONE);
        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertSame(1, preg_match(Uuid::UUID_PATTERN, (string) $uuid));
    }

    public function testShouldNotAllowInvalidStringUuid()
    {
        $this->expectException(InvalidUuidException::class);
        $uuid = Uuid::fromString('not a uuid');
    }

    public function testShouldCoerceToString()
    {
        $uuid = Uuid::generate();
        $this->assertInternalType('string', (string) $uuid);
    }

    public function testShouldProvidePrimitiveValue()
    {
        $uuid = Uuid::fromString(self::UUID_ONE);
        $this->assertInternalType('string', $uuid->toPrimitive());
        $this->assertSame(self::UUID_ONE, $uuid->toPrimitive());
    }

    public function testShouldHashValue()
    {
        $uuid = Uuid::fromString(self::UUID_ONE);
        $this->assertSame(self::UUID_ONE, $uuid->hash());
    }

    public function testShouldTestForEquality()
    {
        $uuidOne = Uuid::fromString(self::UUID_ONE);
        $uuidTwo = Uuid::fromString(self::UUID_ONE);
        $uuidThree = Uuid::generate();

        $this->assertTrue($uuidOne->equals($uuidTwo));
        $this->assertFalse($uuidOne->equals($uuidThree));
    }
}
