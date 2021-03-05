<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\PropertyInfo\Tests\Fixtures;

class DocInterfacedDummy implements DummyInterface
{
    /**
     * @var DummyInterface
     */
    private $propA;

    /**
     * @param DummyInterface $propB
     */
    public function __construct($propB)
    {

    }

    /**
     * @return DummyInterface
     */
    public function getPropC()
    {

    }

    /**
     * @param DummyInterface $propD
     */
    public function setPropD($propD)
    {

    }
}
