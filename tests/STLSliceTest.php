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

use php3d\stlslice\STLSlice;

/**
 * @covers STLSlice
 */
class STLSliceTest extends \PHPUnit_Framework_TestCase
{
    private $stlFileString = <<<EOT
solid test
facet normal 0.5651193 -0.07131607 0.8219211
outerloop
    vertex -71.74323 47.70205 4.666243
    vertex -72.13071 47.70205 4.932664
    vertex -72.1506 47.2273 4.905148
endloop
endfacet
facet normal 0.5651276 -0.0713266 0.8219144
outerloop
    vertex -72.1506 47.2273 4.905148
    vertex -71.7618 47.2273 4.637817
    vertex -71.74323 47.70205 4.666243
endloop
endfacet
facet normal 0.5664103 -0.02379302 0.8237799
outerloop
    vertex -71.7618 47.2273 4.637817
    vertex -72.1506 47.2273 4.905148
    vertex -72.15724 46.75148 4.895968
endloop
endfacet
endsolid
EOT;

    private $sliceArray = array(
        array(
            "height" => 47.70205,
            "width" => 72.107936489759865,
            "name" => "test0",
            "points" => array(
                "2" => array(
                    array(
                        0,
                        46.810805299015,
                        4.8637817
                    ),
                    array(
                        0.017498676113121,
                        47.2273,
                        4.8637817
                    ),
                    array(
                        0.017498676113107,
                        47.2273,
                        4.8637817,
                    ),
                    array(
                        0.027872433465518,
                        47.30950276229,
                        4.8637817
                    ),
                    array(
                        0.027872433465518,
                        47.30950276229,
                        4.8637817
                    ),
                    array(
                        0.077408208182959,
                        47.70205,
                        4.8637817
                    )
                ),
                "1" => array(
                    array(
                        0.15318166499452,
                        46.995123779284,
                        4.7637817
                    ),
                    array(
                        0.16293635449684,
                        47.2273,
                        4.7637817
                    ),
                    array(
                        0.19838791032872,
                        47.50822191844,
                        4.7637817
                    ),
                    array(
                        0.19838791032872,
                        47.50822191844,
                        4.7637817
                    ),
                    array(
                        0.22284719384851,
                        47.70205,
                        4.7637817
                    )
                )
            )
        )
    );


    function testSlice()
    {
        $stl = STL::fromString($this->stlFileString);
        $this->assertEquals(
            $this->sliceArray,
            (new STLSlice($stl, 10))->slice()
        );
    }
}