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

use php3d\stlslice\Geometry;
use php3d\stlslice\Vector;

/**
 * @covers Geometry
 */
class GeometryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        bcscale(16);
    }

    /**
     * @expectedException \Exception
     */
    public function testIntersectFailsForLineParallelToPlane()
    {
        (new Geometry())->intersect(
            new Vector(1, 1, 1),
            new Vector(1, 1, 1),
            new Vector(1, 1, 1),
            new Vector(1, 1, 1)
        );
    }

    public function testIntersectLineWithPlane()
    {
        $this->assertEquals(new Vector(
            1, 1, 1.99999999999999
        ), (new Geometry())->intersect(
            new Vector(1, 1, 1),
            new Vector(1, 1, 10),
            new Vector(1, 1, 2),
            new Vector(0, 0, 1)
        ));
    }
}