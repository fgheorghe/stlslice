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

/**
 * Class Vector. Provides vector math.
 */
class Vector
{
    /**
     * @var float
     */
    private $x;

    /**
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }

    /**
     * @param float $x
     * @return Vector
     */
    private function setX(float $x): Vector
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return float
     */
    public function getY(): float
    {
        return $this->y;
    }

    /**
     * @param float $y
     * @return Vector
     */
    private function setY(float $y): Vector
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @return float
     */
    public function getZ(): float
    {
        return $this->z;
    }

    /**
     * @param float $z
     * @return Vector
     */
    private function setZ(float $z): Vector
    {
        $this->z = $z;
        return $this;
    }

    /**
     * @var float
     */
    private $y;

    /**
     * @var float
     */
    private $z;

    public function __construct(float $x, float $y, float $z)
    {
        $this->setX($x);
        $this->setY($y);
        $this->setZ($z);
    }

    /**
     * From array constructor.
     *
     * @param array $vectorArray
     * @return Vector
     */
    public static function fromArray(array $vectorArray) : Vector
    {
        return new self(
            $vectorArray[0],
            $vectorArray[1],
            $vectorArray[2]
        );
    }

    /**
     * Adds this vector to another and returns the resulting vector.
     *
     * @param Vector $vector
     * @return Vector
     */
    public function add(Vector $vector) : Vector
    {
        return new Vector(
            (float)bcadd($this->getX(), $vector->getX()),
            (float)bcadd($this->getY(), $vector->getY()),
            (float)bcadd($this->getZ(), $vector->getZ())
        );
    }

    /**
     * Substracts a vector from this vector and returns the resulting vector.
     *
     * @param Vector $vector
     * @return Vector
     */
    public function sub(Vector $vector) : Vector
    {
        return new Vector(
            (float)bcsub($this->getX(), $vector->getX()),
            (float)bcsub($this->getY(), $vector->getY()),
            (float)bcsub($this->getZ(), $vector->getZ())
        );
    }

    /**
     * Dot value of two vectors and returns the resulting vector.
     *
     * @param Vector $vector
     * @return float
     */
    public function dot(Vector $vector) : float
    {
        return (float) bcadd(
            bcadd(
                bcmul($this->getX(), $vector->getX()),
                bcmul($this->getY(), $vector->getY())
            ),
            bcmul($this->getZ(), $vector->getZ())
        );
    }

    /**
     * Multiplies this vector to a scalar and returns the resulting vector.
     *
     * @param float $scalar
     * @return Vector
     */
    public function multiplyScalar(float $scalar) : Vector
    {
        return new Vector(
            (float)bcmul($this->getX(), $scalar),
            (float)bcmul($this->getY(), $scalar),
            (float)bcmul($this->getZ(), $scalar)
        );
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return array(
            $this->getX(),
            $this->getY(),
            $this->getZ()
        );
    }
}