<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Number;

use Throwable;
use PHPUnit\Framework\TestCase;
use Jorpo\ValueObject\ValueObject;

class IntValueTest extends TestCase
{
    public function testShouldConstructWithNumbers()
    {
        $subject = new IntValue(1234);
        $this->assertInstanceOf(IntValue::class, $subject);

        $subject = new IntValue(-123);
        $this->assertInstanceOf(IntValue::class, $subject);

        $subject = new IntValue(0123);
        $this->assertInstanceOf(IntValue::class, $subject);

        $subject = new IntValue(0x1A);
        $this->assertInstanceOf(IntValue::class, $subject);

        $subject = new IntValue(0b11111111);
        $this->assertInstanceOf(IntValue::class, $subject);
    }

    public function testShouldNotConstructWithString()
    {
        try {
            $subject = new IntValue('something');
        } catch (Throwable $error) {
            $this->assertTrue(false !== strpos($error->getMessage(), 'must be of the type integer, string given'));
        }
    }

    public function testShouldNotConstructWithFloat()
    {
        try {
            $subject = new IntValue(1.23);
        } catch (Throwable $error) {
            $this->assertTrue(false !== strpos($error->getMessage(), 'must be of the type integer, float given'));
        }
    }

    public function testShouldCoerceToString()
    {
        $subject = new IntValue(101);
        $this->assertSame('101', (string) $subject);
    }

    public function testShouldProvidePrimitiveValue()
    {
        $subject = new IntValue(101);
        $this->assertSame(101, $subject->toPrimitive());
    }

    public function testShouldHashValue()
    {
        $subject = new IntValue(101);
        $this->assertSame('101', $subject->hash());
    }

    public function testShouldTestForEquality()
    {
        $subjectOne = new IntValue(101);
        $subjectTwo = new IntValue(101);
        $subjectThree = new IntValue(202);

        $this->assertTrue($subjectOne->equals($subjectTwo));
        $this->assertFalse($subjectOne->equals($subjectThree));
    }
}
