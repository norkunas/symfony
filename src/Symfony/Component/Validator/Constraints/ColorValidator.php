<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ColorValidator extends ConstraintValidator
{
    public const PATTERN_HEX = '/^#[0-9a-f]{3}([0-9a-f]{3})?$/';
    public const PATTERN_RGB = '/^rgb\(\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])%?\s*,\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])%?\s*,\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])%?\s*\)$/';
    public const PATTERN_RGBA = '/^rgba\(\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])%?\s*,\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])%?\s*,\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])%?\s*,\s*((0.[1-9])|[01])\s*\)$/';
    public const PATTERN_HSL = '/^hsl\(\s*(0|[1-9]\d?|[12]\d\d|3[0-5]\d)\s*,\s*((0|[1-9]\d?|100)%)\s*,\s*((0|[1-9]\d?|100)%)\s*\)$/';

    private const FORMAT_PATTERNS = [
        'hex' => self::PATTERN_HEX,
        'rgb' => self::PATTERN_RGB,
        'rgba' => self::PATTERN_RGBA,
        'hsl' => self::PATTERN_HSL,
    ];

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Color) {
            throw new UnexpectedTypeException($constraint, Color::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;

        if ('' === $value) {
            return;
        }

        if (null !== $constraint->normalizer) {
            $value = ($constraint->normalizer)($value);
        }

        if (null !== $constraint->format) {
            if (!\in_array($constraint->format, Color::$colorFormats, true)) {
                throw new \InvalidArgumentException(sprintf('The "%s::$format" parameter value is not valid.', get_debug_type($constraint)));
            }

            if (!preg_match(self::FORMAT_PATTERNS[$constraint->format], $value) !== 1) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setParameter('{{ formats }}', $this->formatValue($constraint->format))
                    ->setCode(Color::INVALID_FORMAT_ERROR)
                    ->addViolation();
            }

            return;
        }


        foreach (self::FORMAT_PATTERNS as $format => $pattern) {
            if (!preg_match($pattern, $value) !== 1) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $this->formatValue($value))
                    ->setParameter('{{ formats }}', $this->formatValues(self::FORMAT_PATTERNS))
                    ->setCode(Color::INVALID_FORMAT_ERROR)
                    ->addViolation();

                break;
            }
        }
    }
}
