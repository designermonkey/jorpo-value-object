<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Number;

use Throwable;
use PHPUnit\Framework\TestCase;
use Jorpo\ValueObject\ValueObject;

class FloatValueTest extends TestCase
{
    public function testShouldConstructWithNumbers()
    {
        $subject = new FloatValue(1.23);
        $this->assertInstanceOf(FloatValue::class, $subject);

        $subject = new IntValue(1234);
        $this->assertInstanceOf(IntValue::class, $subject);

        $subject = new FloatValue(-1.2);
        $this->assertInstanceOf(FloatValue::class, $subject);

        $subject = new IntValue(-123);
        $this->assertInstanceOf(IntValue::class, $subject);


        $subject = new FloatValue(0123);
        $this->assertInstanceOf(FloatValue::class, $subject);

        $subject = new FloatValue(0x1A);
        $this->assertInstanceOf(FloatValue::class, $subject);

        $subject = new FloatValue(0b11111111);
        $this->assertInstanceOf(FloatValue::class, $subject);
    }

    public function testShouldNotConstructWithString()
    {
        try {
            $subject = new FloatValue('something');
        } catch (Throwable $error) {
            $this->assertTrue(false !== strpos($error->getMessage(), 'must be of the type float, string given'));
        }
    }

    public function testShouldCoerceToString()
    {
        $subject = new FloatValue(1.23);
        $this->assertSame('1.23', (string) $subject);
    }

    public function testShouldProvidePrimitiveValue()
    {
        $subject = new FloatValue(1.23);
        $this->assertSame(1.23, $subject->toPrimitive());
    }

    public function testShouldHashValue()
    {
        $subject = new FloatValue(1.23);
        $this->assertSame('1.23', $subject->hash());
    }

    public function testShouldTestForEquality()
    {
        $subjectOne = new FloatValue(1.23);
        $subjectTwo = new FloatValue(1.23);
        $subjectThree = new FloatValue(2.34);

        $this->assertTrue($subjectOne->equals($subjectTwo));
        $this->assertFalse($subjectOne->equals($subjectThree));
    }
}
