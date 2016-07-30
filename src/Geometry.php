<?php
/*
 * This file is part of the STLSLICE package.
 *
 * (c) Grosan Flaviu Gheorghe <fgheorghe@grosan.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace php3d\stlslice;

use php3d\stl\STL;

class Geometry {
    /**
     * @var int
     */
    private $precision;

    /**
     * @var STL
     */
    private $stl;

    /**
     * @return STL
     */
    public function getStl(): STL
    {
        return $this->stl;
    }

    /**
     * @param STL $stl
     * @return Geometry
     */
    public function setStl(STL $stl): Geometry
    {
        $this->stl = $stl;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     * @return Geometry
     */
    public function setPrecision(int $precision): Geometry
    {
        $this->precision = $precision;
        return $this;
    }

    /**
     * Geometry constructor.
     * @param STL $stl
     * @param float $precision
     */
    public function __construct(STL $stl, float $precision)
    {
        $this->setStl($stl)->setPrecision($precision);
    }
}