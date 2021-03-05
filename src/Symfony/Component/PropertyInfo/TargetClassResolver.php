<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\PropertyInfo;

/**
 * @final
 */
class TargetClassResolver
{
    /**
     * @var array<class-string, class-string>
     */
    private $targetClasses;

    public function __construct(array $targetClasses = [])
    {
        $this->targetClasses = $targetClasses;
    }

    /**
     * @param class-string $target
     * @param class-string $class
     */
    public function addTargetClass(string $target, string $class): void
    {
        $this->targetClasses[$target] = $class;
    }

    /**
     * @param class-string $target
     *
     * @return class-string|null
     */
    public function getTargetClass(string $target): ?string
    {
        return $this->targetClasses[$target] ?? null;
    }
}
