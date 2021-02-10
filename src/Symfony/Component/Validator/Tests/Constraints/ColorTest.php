<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Tests\Constraints;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Color;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;

class ColorTest extends TestCase
{
    public function setFormatCanBeSet()
    {
        $subject = new Color(['format' => 'rgba']);

        $this->assertEquals('rgba', $subject->format);
    }

    public function testUnknownFormatTriggersException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "format" parameter value is not valid.');

        new Email(['format' => 'rgbz']);
    }

    public function testNormalizerCanBeSet()
    {
        $email = new Color(['normalizer' => 'trim']);

        $this->assertEquals('trim', $email->normalizer);
    }

    public function testInvalidNormalizerThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "normalizer" option must be a valid callable ("string" given).');

        new Color(['normalizer' => 'Unknown Callable']);
    }

    public function testInvalidNormalizerObjectThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "normalizer" option must be a valid callable ("stdClass" given).');

        new Color(['normalizer' => new \stdClass()]);
    }

    /**
     * @requires PHP 8
     */
    public function testAttribute()
    {
        $metadata = new ClassMetadata(ColorDummy::class);
        (new AnnotationLoader())->loadClassMetadata($metadata);

        [$aConstraint] = $metadata->properties['a']->constraints;
        self::assertNull($aConstraint->format);
        self::assertNull($aConstraint->normalizer);

        [$bConstraint] = $metadata->properties['b']->constraints;
        self::assertSame('myMessage', $bConstraint->message);
        self::assertSame('rgb', $bConstraint->format);
        self::assertSame('trim', $bConstraint->normalizer);

        [$cConstraint] = $metadata->properties['c']->getConstraints();
        self::assertSame(['my_group'], $cConstraint->groups);
        self::assertSame('some attached data', $cConstraint->payload);
    }
}

class ColorDummy
{
    #[Color]
    private $a;

    #[Color(message: 'myMessage', format: Color::COLOR_FORMAT_RGB, normalizer: 'trim')]
    private $b;

    #[Color(groups: ['my_group'], payload: 'some attached data')]
    private $c;
}
