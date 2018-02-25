<?php declare(strict_types=1);

namespace Jorpo\ValueObject\Identity;

use Throwable;
use PHPUnit\Framework\TestCase;
use Jorpo\ValueObject\ValueObject;
use Jorpo\ValueObject\Identity\Exception\InvalidEmailAddressException;

class EmailAddressTest extends TestCase
{
    const VALID = [
        'email@example.com',
        'firstname.lastname@example.com',
        'email@subdomain.example.com',
        'firstname+lastname@example.com',
        'email@example.com',
        '1234567890@example.com',
        'email@example-one.com',
        '_______@example.com',
        'email@example.name',
        'email@example.museum',
        'email@example.co.jp',
        'firstname-lastname@example.com',
    ];

    const INVALID = [
        'plainaddress',
        '#@%^%#$@#$@#.com',
        '@example.com',
        'Joe Smith <email@example.com>',
        'email.example.com',
        'email@example@example.com',
        '.email@example.com',
        'email.@example.com',
        'email..email@example.com',
        'あいうえお@example.com',
        'email@example.com (Joe Smith)',
        'email@example',
        'email@-example.com',
        'email@example.web',
        'email@111.222.333.44444',
        'email@example..com',
        'Abc..123@example.com',
        '“(),:;<>[\]@example.com',
        'just"not"right@example.com',
        'this\ is"really"not\allowed@example.com',
    ];

    public function testShouldConstructWithEmailAddress()
    {
        foreach (self::VALID as $emailAddress) {
            $subject = new EmailAddress($emailAddress);
            $this->assertInstanceOf(EmailAddress::class, $subject);
        }
    }

    public function testShouldNotConstructWithAnyString()
    {
        foreach (self::INVALID as $emailAddress) {
            try {
                $subject = new EmailAddress($emailAddress);
            } catch (Throwable $exception) {
                $this->assertInstanceOf(InvalidEmailAddressException::class, $exception);
            }
        }
    }

    public function testShouldCoerceToString()
    {
        $subject = new EmailAddress(self::VALID[0]);
        $this->assertSame(self::VALID[0], (string) $subject);
    }

    public function testShouldProvidePrimitiveValue()
    {
        $subject = new EmailAddress(self::VALID[0]);
        $this->assertSame(self::VALID[0], $subject->toPrimitive());
    }

    public function testShouldHashValue()
    {
        $subject = new EmailAddress(self::VALID[0]);
        $this->assertSame(self::VALID[0], $subject->hash());
    }

    public function testShouldTestForEquality()
    {
        $subjectOne = new EmailAddress(self::VALID[0]);
        $subjectTwo = new EmailAddress(self::VALID[0]);
        $subjectThree = new EmailAddress(self::VALID[1]);

        $this->assertTrue($subjectOne->equals($subjectTwo));
        $this->assertFalse($subjectOne->equals($subjectThree));
    }
}
