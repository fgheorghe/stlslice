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

class STL2Svg {
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
     * @return STL2Svg
     */
    public function setCoordinates(array $coordinates): STL2Svg
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    public function __construct(array $coordinates)
    {
        $this->setCoordinates($coordinates);
    }

    public function toSvgString() : string
    {
        $coordinates = $this->getCoordinates();
        $height = $coordinates[0]["height"];
        $width = $coordinates[0]["height"];
        $svg = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>
<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.0//EN\" \"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd\">
<svg xmlns=\"http://www.w3.org/2000/svg\" height=\"" . $height . "\" width=\"" . $width . "\">\n";

        foreach ($this->getCoordinates() as $coordinate) {
            $name = $coordinate["name"];
            foreach ($coordinate["points"] as $layer => $points) {
                $coordinates = array();
                foreach ($points as $point) {
                    $coordinates[] = $point[0] . "," . $point[1];
                }
                $svg .= "<g id=\"layer" . $layer . "\" z=\"" . $point[2] . "\">\n";
                $svg .= "<polygon name=\"" . $name . "\" type=\"contour\" points=\"" . implode(" ",
                        $coordinates) . " " . $coordinates[0] . "\" style=\"fill:lime;stroke:purple;stroke-width:1\" />";
                $svg .= "</g>\n";
            }
        }

        $svg .= "</svg>\n";

        return $svg;
    }
}