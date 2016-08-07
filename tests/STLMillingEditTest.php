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

use php3d\stlslice\Examples\STLMillingEdit;

class STLMillingEditTest extends \PHPUnit_Framework_TestCase
{
    private $testStlArray = array(
        "name" => "TEST2",
        "facets" => array(
            array(
                "coordinates" => array(
                    0,
                    1,
                    2
                ),
                "vertex" => array(
                    array(
                        3, 4, 5
                    ),
                    array(
                        5, 4, 6
                    ),
                    array(
                        7, 8, 9
                    )
                )
            ),
            array(
                "coordinates" => array(
                    10,
                    11,
                    12
                ),
                "vertex" => array(
                    array(
                        12, 14, 15
                    ),
                    array(
                        16, 17, 18
                    ),
                    array(
                        19, 20, 21
                    )
                )
            )
        )
    );

    public function tearDown()
    {
        \Mockery::close();
    }

    public function testLowestVertexX() {
        $stlMillingEditor = new STLMillingEdit($this->testStlArray);

        $this->assertEquals(
            3,
            $stlMillingEditor->getLowestVertexX()
        );
    }

    public function testHighestVertexX() {
        $stlMillingEditor = new STLMillingEdit($this->testStlArray);

        $this->assertEquals(
            19,
            $stlMillingEditor->getHighestVertexX()
        );
    }

    public function testRemoveLowestXVertices() {
        $stlMillingEditor = new STLMillingEdit($this->testStlArray);

        $this->assertEquals(
            array(
                "name" => "TEST2",
                "facets" => array(
                    array(
                        "coordinates" => array(
                            10,
                            11,
                            12
                        ),
                        "vertex" => array(
                            array(
                                12, 14, 15
                            ),
                            array(
                                16, 17, 18
                            ),
                            array(
                                19, 20, 21
                            )
                        )
                    )
                )
            ),
            $stlMillingEditor->removeLowestXVertices()->getStlFileContentArray()
        );
    }

    public function testRemoveHighestXVertices() {
        $stlMillingEditor = new STLMillingEdit($this->testStlArray);

        $this->assertEquals(
            array(
                "name" => "TEST2",
                "facets" => array(
                    array(
                        "coordinates" => array(
                            0,
                            1,
                            2
                        ),
                        "vertex" => array(
                            array(
                                3, 4, 5
                            ),
                            array(
                                5, 4, 6
                            ),
                            array(
                                7, 8, 9
                            )
                        )
                    )
                )
            ),
            $stlMillingEditor->removeHighestXVertices()->getStlFileContentArray()
        );
    }

    public function testExtractMillingContent() {
        $stlMillingEditor = new STLMillingEdit($this->testStlArray);

        $this->assertEquals(
            array(
                "name" => "TEST2",
                "facets" => array()
            ),
            $stlMillingEditor->extractMillingContent()->getStlFileContentArray()
        );
    }
}