<?php
/*
 * This file is part of the STLSLICE package.
 *
 * (c) Grosan Flaviu Gheorghe <fgheorghe@grosan.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace php3d\stlslice\Examples;

/**
 * Class STL2GCode. Milling. This is an example file, change to suit your needs.
 * @package php3d\stlslice\Examples
 */
class STL2GCode
{
    /**
     * @var int
     */
    private $feedRate;

    /**
     * @return int
     */
    public function getFeedRate(): int
    {
        return $this->feedRate;
    }

    /**
     * @param int $feedRate
     * @return STL2GCode
     */
    public function setFeedRate(int $feedRate): STL2GCode
    {
        $this->feedRate = $feedRate;
        return $this;
    }

    /**
     * @var array
     */
    private $coordinates;

    /**
     * @return array
     */
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    /**
     * @param array $coordinates
     * @return STL2GCode
     */
    public function setCoordinates(array $coordinates): STL2GCode
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * STL2GCode constructor.
     * @param array $coordinates
     * @param int $feedRate
     */
    public function __construct(array $coordinates, int $feedRate)
    {
        $this->setCoordinates($coordinates);
        $this->setFeedRate($feedRate);
    }

    /**
     * @return string
     */
    public function toGCodeString() : string
    {
        $gcode = "F " . $this->getFeedRate() . "\n";

        foreach ($this->getCoordinates() as $coordinate) {
            foreach ($coordinate["points"] as $layer => $points) {
                $gcode .= $this->getPolygonCoordinates(
                    $points,
                    round($coordinate["width"]),
                    round($coordinate["height"])
                );
            }
        }

        return $gcode;
    }

    public function getPolygonCoordinates(array $points, int $width, int $height)
    {
        $image = imagecreatetruecolor($width, $height);
        $polygonColor = imagecolorallocate($image, 0, 0, 255);
        $imagePoints = array();
        $z = 0;
        $highestZ = 0;
        foreach ($points as $point) {
            $imagePoints[] = $point[0];
            $imagePoints[] = $point[1];
            $z = $point[2];
            if ($z > $highestZ) $highestZ = $z;
        }

        imagefilledpolygon($image, $imagePoints, count($imagePoints) / 2, $polygonColor);
        $result = "G1 Z" . $highestZ . "\n";
        for ($k = 0; $k < $height - 1; $k++) {
            for ($l = 0; $l < $width - 1; $l++) {
                $coordinates[$k][$l] = imagecolorat($image, $l, $k);
                if ($coordinates[$k][$l] != 0) {
                    $result .= "G1 X" . $k . " Y" . $l . " Z" . $z . "\n";
                }
            }
        }
        $result .= "G1 Z" . $highestZ . "\n";

        imagedestroy($image);
        return $result;
    }
}