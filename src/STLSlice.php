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
use php3d\stl\STLSplit;

/**
 * Class Geometry. Provides logic for intersecting a line with a plane.
 *
 * NOTE: Requires: bcscale(16);
 *
 */
class STLSlice
{
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
     * @return STLSlice
     */
    public function setStl(STL $stl): STLSlice
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
     * @return STLSlice
     */
    public function setPrecision(int $precision): STLSlice
    {
        $this->precision = $precision;
        return $this;
    }

    /**
     * @param STL $stl
     * @param float $precision
     */
    public function __construct(STL $stl, float $precision)
    {
        $this->setStl($stl)->setPrecision($precision);
    }

    /**
     * Returns an array of layers and coordinates for points for each layer for the 3D object.
     *
     * @return array
     */
    public function slice() : array
    {
        $objects = (new STLSplit($this->getStl()))->split();
        $layersNames = [];

        $absoluteHighestZ = 0;
        $zeds = array();
        foreach ($objects as $key => $object) {
            $highestZ = null;
            $lowestZ = null;
            if ($highestZ > $absoluteHighestZ) $absoluteHighestZ = $highestZ;
            $lines = $this->extractLinesFromStl($object, $highestZ, $lowestZ);

            $planeNormalCoordinate = new Vector(0, 0, 1);
            $layers = array();
            $layer = 0;
            for ($i = $lowestZ; $i < $highestZ * $this->getPrecision(); $i++) {
                $planePointCoordinate = new Vector(0, 0, $i / $this->getPrecision());
                $layers[$layer] = array(); // Stores dot vectors.
                $intersectingLines = $this->findAllLinesIntersectingWithPlane($lines, $planePointCoordinate,
                    $layers[$layer]);
                foreach ($intersectingLines as $intersectingLine) {
                    $point = (new Geometry())->intersect(
                        $intersectingLine[0],
                        $intersectingLine[1],
                        $planePointCoordinate,
                        $planeNormalCoordinate
                    );
                    $layers[$layer][] = $point;

                }
                if (count($layers[$layer])) {
                    $layer++;
                }
            }

            $objectsLayers[] = $layers;
            $layersNames[] = $object->getSolidName();
            $zeds[$key] = $highestZ;
        }

        $lowestX = 0;
        $highestX = 0;
        $lowestY = 0;
        $highestY = 0;
        for ($k = 0; $k < count($objectsLayers); $k++) {
            for ($i = 0; $i < count($objectsLayers[$k]); $i++) {
                foreach ($objectsLayers[$k][$i] as $dot) {
                    if ($dot->getX() < $lowestX) {
                        $lowestX = $dot->getX();
                    }
                    if ($dot->getY() < $lowestY) {
                        $lowestY = $dot->getY();
                    }
                    if ($dot->getX() > $highestX) {
                        $highestX = $dot->getX();
                    }
                    if ($dot->getY() > $highestY) {
                        $highestY = $dot->getY();
                    }
                }
            }
        }

        $addX = 0;
        $addY = 0;
        if ($lowestX < 0) $addX = -1 * $lowestX;
        if ($lowestY < 0) $addY = -1 * $lowestY;

        $objectsLayersArray = [];
        foreach ($objectsLayers as $key => $layers) {
            $objectsLayersArray[] = $this->createObjectLayers(
                $layers,
                $addX,
                $addY,
                $lowestX,
                $lowestY,
                $highestX,
                $highestY,
                $layersNames[$key]
            );
        }

        return $objectsLayersArray;
    }

    /**
     * Creates SVG polygon.
     *
     * @param array $layers
     * @param float $addX
     * @param float $addY
     * @param float $lowestX
     * @param float $lowestY
     * @param float $highestX
     * @param float $highestY
     * @return array
     */
    private function createObjectLayers(array $layers, float $addX, float $addY, float $lowestX, float $lowestY, float $highestX, float $highestY, string $name) : array
    {
        $result = [];

        for ($i = count($layers) - 1; $i > 0; $i--) {
            $layers[$i] = $this->sortPolygonCoordinates($layers[$i]);
            $result[$i] = array();
            foreach ($layers[$i] as $dot) {
                $_dot = array($addX + $dot["x"], $addY + $dot["y"], $dot["z"]);
                if (!in_array($_dot, $result[$i])) {
                    $result[$i][] = $_dot;
                }
            }
        }

        $width = (($lowestX < 0) ? -1 * $lowestX : $lowestX) + (($highestX < 0) ? -1 * $highestX : $highestX);
        $height = (($lowestY < 0) ? -1 * $lowestY : $lowestY) + (($highestY < 0) ? -1 * $highestY : $highestY);

        return array(
            "height" => $height,
            "width" => $width,
            "name" => $name,
            "points" => $result
        );
    }

    // http://stackoverflow.com/questions/29610770/draw-a-polygon-between-coordinates-preventing-intersects
    private function findCenter($coordinates) : array
    {
        $x = 0; $y = 0;
        foreach ($coordinates as $coordinate) {
            $x += $coordinate->getX();
            $y += $coordinate->getY();
        }

        return array(
            "x" => $x / count($coordinates),
            "y" => $y / count($coordinates)
        );
    }

    private function findAngles(array $centre, array $coordinates) : array
    {
        $angles = array();

        foreach ($coordinates as $coordinate) {
            $angle = atan2(
                $coordinate->getX() - $centre["x"],
                $coordinate->getY() - $centre["y"]
            );

            $angles[] = array(
                "x" => $coordinate->getX(),
                "y" => $coordinate->getY(),
                "z" => $coordinate->getZ(),
                "angle" => $angle
            );
        }

        return $angles;
    }

    private function sortPolygonCoordinates(array $coordinates) {
        $angles = $this->findAngles($this->findCenter($coordinates), $coordinates);

        usort($angles, function($a, $b) {
            if ($a["angle"] > $b["angle"]) return 1;
            else if ($a["angle"] < $b["angle"]) return -1;
            return 0;
        });

        return $angles;
    }

    /**
     * Find all the lines that WILL intersect with a plane.
     *
     * @param array $lines
     * @param Vector $planePointCoordinate
     * @param $layer array
     * @return array
     */
    private function findAllLinesIntersectingWithPlane(array $lines, Vector $planePointCoordinate, array &$layer) : array
    {
        $z = $planePointCoordinate->getZ();
        $intersectingLines = array();
        foreach ($lines as $line) {
            if (($line[0]->getZ() < $z && $line[1]->getZ() > $z)
                || ($line[1]->getZ() < $z && $line[0]->getZ() > $z)){
                $intersectingLines[] = $line;
            }
            if ($line[0]->getZ() == $z) {
                $layer[] = $line[0];
            }
            if ($line[1]->getZ() == $z) {
                $layer[] = $line[1];
            }
        }

        return $intersectingLines;
    }

    /**
     * Converts all STL facets to an array of lines with start and end vector coordinates.
     *
     * Sets the highest and lowest Z coordinates of all lines to figure out the highest and lowest layers.
     *
     * @param STL $stl
     * @param float $highestZ
     * @param float $lowestZ
     * @return array
     */
    private function extractLinesFromStl(
        STL $stl,
        &$highestZ,
        &$lowestZ
    ) : array
    {
        $stlArray = $stl->toArray();
        $lines = array();

        foreach ($stlArray["facets"] as $facet) {
            $startVector = Vector::fromArray($facet["vertex"][0]);
            $endVector = Vector::fromArray($facet["vertex"][1]);
            $lines[] = array(
                $startVector,
                $endVector
            );
            if (is_null($highestZ)) {
                $highestZ = $startVector->getZ();
            }
            if (is_null($lowestZ)) {
                $lowestZ = $startVector->getZ();
            }
            if ($startVector->getZ() > $highestZ) {
                $highestZ = $startVector->getZ();
            }
            if ($endVector->getZ() > $highestZ) {
                $highestZ = $endVector->getZ();
            }
            if ($startVector->getZ() < $lowestZ) {
                $lowestZ = $startVector->getZ();
            }
            if ($endVector->getZ() < $lowestZ) {
                $lowestZ = $endVector->getZ();
            }

            $startVector = Vector::fromArray($facet["vertex"][1]);
            $endVector = Vector::fromArray($facet["vertex"][2]);
            $lines[] = array(
                $startVector,
                $endVector
            );
            if ($startVector->getZ() > $highestZ) {
                $highestZ = $startVector->getZ();
            }
            if ($endVector->getZ() > $highestZ) {
                $highestZ = $endVector->getZ();
            }
            if ($startVector->getZ() < $lowestZ) {
                $lowestZ = $startVector->getZ();
            }
            if ($endVector->getZ() < $lowestZ) {
                $lowestZ = $endVector->getZ();
            }

            $startVector = Vector::fromArray($facet["vertex"][2]);
            $endVector =  Vector::fromArray($facet["vertex"][0]);
            $lines[] = array(
                $startVector,
                $endVector
            );
            if ($startVector->getZ() > $highestZ) {
                $highestZ = $startVector->getZ();
            }
            if ($endVector->getZ() > $highestZ) {
                $highestZ = $endVector->getZ();
            }
            if ($startVector->getZ() < $lowestZ) {
                $lowestZ = $startVector->getZ();
            }
            if ($endVector->getZ() < $lowestZ) {
                $lowestZ = $endVector->getZ();
            }
        }

        return $lines;
    }
}