<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Boolean;

use PHPUnit\Framework\TestCase;
use Jorpo\ValueObject\Boolean\Exception\InvalidBoolValueException;
use Jorpo\ValueObject\Boolean\Exception\InvalidBoolValueFormatException;

class BoolValueTest extends TestCase
{
    public function testShouldConstructWithPrimitiveBoolean()
    {
        $subject = $this->createInstance(true);
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(true, $subject->toPrimitive());
        $this->assertTruthyFormats($subject);

        $subject = $this->createInstance(false);
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(false, $subject->toPrimitive());
        $this->assertFalseyFormats($subject);
    }

    public function testShouldConstructWithStrings()
    {
        $subject = $this->createInstance('true');
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(true, $subject->toPrimitive());
        $this->assertTruthyFormats($subject);

        $subject = $this->createInstance('false');
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(false, $subject->toPrimitive());
        $this->assertFalseyFormats($subject);

        $subject = $this->createInstance('yes');
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(true, $subject->toPrimitive());
        $this->assertTruthyFormats($subject);

        $subject = $this->createInstance('no');
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(false, $subject->toPrimitive());
        $this->assertFalseyFormats($subject);

        $subject = $this->createInstance('on');
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(true, $subject->toPrimitive());
        $this->assertTruthyFormats($subject);

        $subject = $this->createInstance('off');
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(false, $subject->toPrimitive());
        $this->assertFalseyFormats($subject);

        $subject = $this->createInstance('1');
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(true, $subject->toPrimitive());
        $this->assertTruthyFormats($subject);

        $subject = $this->createInstance('0');
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(false, $subject->toPrimitive());
        $this->assertFalseyFormats($subject);

        $subject = $this->createInstance(1);
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(true, $subject->toPrimitive());
        $this->assertTruthyFormats($subject);

        $subject = $this->createInstance(0);
        $this->assertInstanceOf(BoolValue::class, $subject);
        $this->assertSame(false, $subject->toPrimitive());
        $this->assertFalseyFormats($subject);
    }

    public function testShouldNotAcceptNullValues()
    {
        $this->expectException(InvalidBoolValueException::class);
        $this->createInstance();
    }

    public function testShouldNotAcceptInvalidStringValues()
    {
        $this->expectException(InvalidBoolValueException::class);
        $this->createInstance('badger');
    }

    public function testShouldNotAcceptInvalidIntegerValues()
    {
        $this->expectException(InvalidBoolValueException::class);
        $this->createInstance(2);
    }

    public function testShouldNotAllowInvalidFormat()
    {
        $this->expectException(InvalidBoolValueFormatException::class);
        $subject = $this->createInstance(0);
        $subject->toFormat(101);
    }

    public function testShouldCoerceToString()
    {
        $subject = $this->createInstance(false);
        $this->assertSame('false', (string) $subject);

        $subject = $this->createInstance('yes');
        $this->assertSame('true', (string) $subject);
    }

    public function testShouldHashValue()
    {
        $subject = $this->createInstance(false);
        $this->assertSame('false', $subject->hash());

        $subject = $this->createInstance('yes');
        $this->assertSame('true', $subject->hash());
    }

    public function testShouldTestForEquality()
    {
        $subjectOne = $this->createInstance('yes');
        $subjectTwo = $this->createInstance(true);
        $subjectThree = $this->createInstance('off');

        $this->assertTrue($subjectOne->equals($subjectTwo));
        $this->assertFalse($subjectOne->equals($subjectThree));
    }

    public function testShouldInvertValue()
    {
        $subject = $this->createInstance('yes');
        $inverted = $subject->invert();

        $this->assertFalse($subject->equals($inverted));
    }

    private function assertTruthyFormats(BoolValue $subject)
    {
        $this->assertSame(1, $subject->toFormat(BoolValue::FORMAT_INT));
        $this->assertSame('1', $subject->toFormat(BoolValue::FORMAT_INTSTR));
        $this->assertSame(true, $subject->toFormat(BoolValue::FORMAT_BOOL));
        $this->assertSame('true', $subject->toFormat(BoolValue::FORMAT_BOOLSTR));
        $this->assertSame('on', $subject->toFormat(BoolValue::FORMAT_ON_OFF));
        $this->assertSame('yes', $subject->toFormat(BoolValue::FORMAT_YES_NO));
    }

    private function assertFalseyFormats(BoolValue $subject)
    {
        $this->assertSame(0, $subject->toFormat(BoolValue::FORMAT_INT));
        $this->assertSame('0', $subject->toFormat(BoolValue::FORMAT_INTSTR));
        $this->assertSame(false, $subject->toFormat(BoolValue::FORMAT_BOOL));
        $this->assertSame('false', $subject->toFormat(BoolValue::FORMAT_BOOLSTR));
        $this->assertSame('off', $subject->toFormat(BoolValue::FORMAT_ON_OFF));
        $this->assertSame('no', $subject->toFormat(BoolValue::FORMAT_YES_NO));
    }

    private function createInstance($value = null): BoolValue
    {
        return new BoolValue($value);
    }
}
