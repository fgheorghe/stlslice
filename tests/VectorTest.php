<?php
/*
 * This file is part of the STLSLICE package.
 *
 * (c) Grosan Flaviu Gheorghe <fgheorghe@grosan.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace php3d\stl;

use php3d\stlslice\Vector;

/**
 * @covers Vector
 */
class VectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Vector
     */
    private $vectorA;
    /**
     * @var Vector
     */
    private $vectorB;

    public function setUp()
    {
        $this->vectorA = new Vector(
            1,
            2,
            3
        );
        $this->vectorB = new Vector(
            4,
            5,
            6
        );
    }

    public function testAddVectors()
    {
        $this->assertEquals(
            new Vector(
                5,
                7,
                9
            ), $this->vectorA->add($this->vectorB)
        );
    }

    public function testSubVectors()
    {
        $this->assertEquals(
            new Vector(
                -3,
                -3,
                -3
            ), $this->vectorA->sub($this->vectorB)
        );
    }

    public function testDotVectors()
    {
        $this->assertEquals(
            32.0, $this->vectorA->dot($this->vectorB)
        );
    }

    public function testMultiplyScalar()
    {
        $this->assertEquals(
            new Vector(
                2,
                4,
                6
            ), $this->vectorA->multiplyScalar(2)
        );
    }

    public function testToArray()
    {
        $this->assertEquals(array(
            1,
            2,
            3
        ), $this->vectorA->toArray());
    }
}