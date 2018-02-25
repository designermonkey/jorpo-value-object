<?php declare(strict_types=1);

namespace Jorpo\ValueObject\String;

use Throwable;
use PHPUnit\Framework\TestCase;
use Jorpo\ValueObject\ValueObject;

class StringValueTest extends TestCase
{
    public function testShouldConstructWithString()
    {
        $subject = new StringValue('something');
        $this->assertInstanceOf(StringValue::class, $subject);
    }

    public function testShouldNotConstructWithNumber()
    {
        try {
            $subject = new StringValue(101);
        } catch (Throwable $error) {
            $this->assertTrue(false !== strpos($error->getMessage(), 'must be of the type string, integer given'));
        }
    }

    public function testShouldCoerceToString()
    {
        $subject = new StringValue('something');
        $this->assertSame('something', (string) $subject);
    }

    public function testShouldProvidePrimitiveValue()
    {
        $subject = new StringValue('something');
        $this->assertSame('something', $subject->toPrimitive());
    }

    public function testShouldHashValue()
    {
        $subject = new StringValue('something');
        $this->assertSame('something', $subject->hash());
    }

    public function testShouldTestForEquality()
    {
        $subjectOne = new StringValue('something');
        $subjectTwo = new StringValue('something');
        $subjectThree = new StringValue('something else');

        $this->assertTrue($subjectOne->equals($subjectTwo));
        $this->assertFalse($subjectOne->equals($subjectThree));
    }
}
