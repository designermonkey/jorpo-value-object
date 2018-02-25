<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Collection;

use Countable;
use Traversable;
use Ds\Set;
use PHPUnit\Framework\TestCase;
use Jorpo\ValueObject\ValueObject;
use Jorpo\ValueObject\ValueObjectDummy;
use Jorpo\ValueObject\Collection\Exception\InvalidObjectTypeException;
use Jorpo\ValueObject\String\StringValue;

class ObjectSetTest extends TestCase
{
    public function testShouldCreateInstance()
    {
        $subject = new ObjectSetFake();
        $this->assertInstanceOf(ObjectSet::class, $subject);
    }

    public function testShouldRestrictContentsToType()
    {
        $subject = new ObjectSetFake();
        $this->assertSame(ValueObjectDummy::class, $subject->objectType());
    }

    public function testShouldFailWithWrongType()
    {
        $this->expectException(InvalidObjectTypeException::class);
        $subject = new ObjectSetFake([
            new StringValue(''),
        ]);
    }

    public function testShoulAllowAccessToSet()
    {
        $subject = new ObjectSetFake([
            $valueObject = new ValueObjectDummy,
        ]);

        $this->assertTrue($subject->asSet()->contains($valueObject));
    }

    public function testShouldAllowAdditionOfValuesToTheSet()
    {
        $subject = new ObjectSetFake();

        $subject->add($valueObject = new ValueObjectDummy);
        $this->assertTrue($subject->asSet()->contains($valueObject));
    }

    public function testShouldNotAllowAdditionOfInvalidValuesToTheSet()
    {
        $this->expectException(InvalidObjectTypeException::class);
        $subject = new ObjectSetFake();
        $subject->add(new StringValue(''));
    }

    public function testShouldMergeAdditionalValuesIntoNewSet()
    {
        $subject = new ObjectSetFake([
            $valueObjectOne = new ValueObjectDummy,
        ]);

        $more = [
            $valueObjectTwo = new ValueObjectDummy,
            $invalidValueObject = new StringValue(''),
        ];

        $merged = $subject->merge($more);

        $this->assertTrue($merged->asSet()->contains($valueObjectOne));
        $this->assertTrue($merged->asSet()->contains($valueObjectTwo));
        $this->assertFalse($merged->asSet()->contains($invalidValueObject));
    }

    public function testShouldUnionWithAnotherSet()
    {
        $subject = new ObjectSetFake([
            $valueObjectOne = new ValueObjectDummy,
        ]);

        $more = new class([
            $valueObjectTwo = new ValueObjectDummy,
            $invalidValueObject = new StringValue(''),
        ]) extends ObjectSet {
            public function objectType(): string
            {
                return ValueObject::class;
            }
        };

        $union = $subject->union($more);

        $this->assertTrue($union->asSet()->contains($valueObjectOne));
        $this->assertTrue($union->asSet()->contains($valueObjectTwo));
        $this->assertFalse($union->asSet()->contains($invalidValueObject));
    }

    public function testShouldXorWithAnotherSetReturningValuesNotInBoth()
    {
        $subject = new ObjectSetFake([
            $valueObjectOne = new ValueObjectDummy,
            $valueObjectTwo = new ValueObjectDummy,
        ]);

        $more = new class([
            $valueObjectTwo,
            $invalidValueObject = new StringValue(''),
        ]) extends ObjectSet {
            public function objectType(): string
            {
                return ValueObject::class;
            }
        };

        $merged = $subject->xor($more);

        $this->assertTrue($merged->asSet()->contains($valueObjectOne));
        $this->assertFalse($merged->asSet()->contains($valueObjectTwo));
        $this->assertFalse($merged->asSet()->contains($invalidValueObject));
    }

    public function testShouldBeTraversable()
    {
        $subject = new ObjectSetFake([
            $valueObjectOne = new ValueObjectDummy,
            $valueObjectTwo = new ValueObjectDummy,
            $valueObjectThree = new ValueObjectDummy,
        ]);

        $this->assertInstanceOf(Traversable::class, $subject);

        $iterations = 0;

        foreach ($subject as $object) {
            $iterations++;
            $this->assertInstanceOf(ValueObjectDummy::class, $object);
        }

        $this->assertSame(3, $iterations);
    }

    public function testShouldBeCountable()
    {
        $subject = new ObjectSetFake([
            $valueObjectOne = new ValueObjectDummy,
            $valueObjectTwo = new ValueObjectDummy,
            $valueObjectThree = new ValueObjectDummy,
        ]);

        $this->assertInstanceOf(Countable::class, $subject);
        $this->assertSame(3, $subject->count());
        $this->assertSame(3, count($subject));
    }

    public function testShouldBeClearable()
    {
        $subject = new ObjectSetFake([
            $valueObjectOne = new ValueObjectDummy,
            $valueObjectTwo = new ValueObjectDummy,
            $valueObjectThree = new ValueObjectDummy,
        ]);
        $subject->clear();
        $this->assertSame(0, $subject->count());
    }

    public function testShouldBeCopyable()
    {
        $subject = new ObjectSetFake([
            $valueObjectOne = new ValueObjectDummy,
            $valueObjectTwo = new ValueObjectDummy,
            $valueObjectThree = new ValueObjectDummy,
        ]);
        $new = $subject->copy();
        $this->assertNotSame($subject, $new);
    }

    public function testShouldCheckIfEmpty()
    {
        $subject = new ObjectSetFake;
        $this->assertTrue($subject->isEmpty());

        $subject = new ObjectSetFake(new Set([
            new ValueObjectDummy,
        ]));
        $this->assertFalse($subject->isEmpty());
    }

    public function testShouldGiveAnArrayCopy()
    {
        $subject = new ObjectSetFake($array = [
            $valueObjectOne = new ValueObjectDummy,
            $valueObjectTwo = new ValueObjectDummy,
            $valueObjectThree = new ValueObjectDummy,
        ]);

        $this->assertSame($array, $subject->toArray());
    }

    public function testShouldSerializeToJson()
    {
        $subject = new ObjectSetFake();

        $this->assertSame([], $subject->jsonSerialize());
        $this->assertEquals('[]', json_encode($subject));
    }
}
