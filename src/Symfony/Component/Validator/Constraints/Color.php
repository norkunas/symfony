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
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Color extends Constraint
{
    public const COLOR_FORMAT_HEX = 'hex';
    public const COLOR_FORMAT_RGB = 'rgb';
    public const COLOR_FORMAT_RGBA = 'rgba';
    public const COLOR_FORMAT_HSL = 'hsl';
    public const COLOR_FORMAT_HSLA = 'hsla';

    public const INVALID_FORMAT_ERROR = 'cf7d615e-a6f8-485d-917a-5adcf2d238a6';

    protected static $errorNames = [
        self::INVALID_FORMAT_ERROR => 'INVALID_FORMAT_ERROR',
    ];

    /**
     * @var string[]
     *
     * @internal
     */
    public static $colorFormats = [
        self::COLOR_FORMAT_HEX,
        self::COLOR_FORMAT_RGB,
        self::COLOR_FORMAT_RGBA,
        self::COLOR_FORMAT_HSL,
        self::COLOR_FORMAT_HSLA,
    ];

    public $message = 'This value is not a valid color format. Available formats are: {{ formats }}.';
    public $format;
    public $normalizer;

    public function __construct(
        array $options = null,
        string $message = null,
        string $format = null,
        callable $normalizer = null,
        array $groups = null,
        $payload = null
    ) {
        if (\is_array($options) && \array_key_exists('color', $options) && !\in_array($options['color'], self::$colorFormats, true)) {
            throw new InvalidArgumentException('The "format" parameter value is not valid.');
        }

        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->format = $format ?? $this->format;
        $this->normalizer = $normalizer ?? $this->normalizer;

        if (null !== $this->normalizer && !\is_callable($this->normalizer)) {
            throw new InvalidArgumentException(sprintf('The "normalizer" option must be a valid callable ("%s" given).', get_debug_type($this->normalizer)));
        }
    }
}
