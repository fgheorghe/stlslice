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
 * Class STLMillingEdit. Used for 'chopping' off parts of an STL file:
 *
 * It will extract edges from a 3D object and keep shapes to be carved.
 *
 * Useful if you pass in a 'wood plank' and want to get GCode for carving out shapes.
 *
 * NOTE: Only to be used for milling! This is highly experimental - validate resulting GCode if used
 * with the GCode generator example class.
 *
 * NOTE: The resulting STL content can not be viewed using a regular STL file viewer, since it does not
 * contain actual STL objects - but rather facets to carve out.
 *
 * TODO: Add link to viewer.
 *
 * @class STLMillingEdit
 */
class STLMillingEdit
{
    private $stlFileContentArray;

    /**
     * @return mixed
     */
    public function getStlFileContentArray() : array
    {
        return $this->stlFileContentArray;
    }

    /**
     * @param array $stlFileContentArray
     * @return STLMillingEdit
     */
    public function setStlFileContentArray(array $stlFileContentArray)
    {
        $this->stlFileContentArray = $stlFileContentArray;
        return $this;
    }

    public function __construct(array $stlFileContentArray)
    {
        $this->setStlFileContentArray($stlFileContentArray);
    }

    /**
     * Lowest possible X coordinate of a vertex.
     *
     * @return float
     */
    public function getLowestVertexX() : float
    {
        $min = null;
        foreach ($this->getStlFileContentArray()["facets"] as $facetNormal) {
            for ($i = 0; $i < 3; $i++) {
                if (is_null($min)) {
                    $min = $facetNormal["vertex"][$i][0];
                    continue;
                }
                if ($min > $facetNormal["vertex"][$i][0]) {
                    $min = $facetNormal["vertex"][$i][0];
                }
            }
        }

        return $min;
    }

    /**
     * Highest possible X coordinate of a vertex.
     *
     * @return float
     */
    public function getHighestVertexX() : float
    {
        $max = null;

        foreach ($this->getStlFileContentArray()["facets"] as $facetNormal) {
            for ($i = 0; $i < 3; $i++) {
                if (is_null($max)) {
                    $max = $facetNormal["vertex"][$i][0];
                    continue;
                }
                if ($max < $facetNormal["vertex"][$i][0]) {
                    $max = $facetNormal["vertex"][$i][0];
                }
            }
        }

        return $max;
    }

    /**
     * Remove all vertices with the lowest X values.
     *
     * @return STLMillingEdit
     */
    public function removeLowestXVertices() : STLMillingEdit
    {
        $facetNormals = [];
        $contentArray = $this->getStlFileContentArray();
        $lowestVertexX = $this->getLowestVertexX();

        foreach ($contentArray["facets"] as $facetNormal) {
            $skip = false;
            for ($i = 0; $i < 3; $i++) {
                if ($facetNormal["vertex"][$i][0] == $lowestVertexX) {
                    $skip = true;
                }
            }
            if (!$skip) {
                $facetNormals[] = $facetNormal;
            }
        }

        $contentArray["facets"] = $facetNormals;

        $this->setStlFileContentArray($contentArray);

        return $this;
    }

    /**
     * Remove all vertices with the highest X values.
     *
     * @return STLMillingEdit
     */
    public function removeHighestXVertices() : STLMillingEdit
    {
        $facetNormals = [];
        $contentArray = $this->getStlFileContentArray();
        $highestVertexX = $this->getHighestVertexX();

        foreach ($contentArray["facets"] as $facetNormal) {
            $skip = false;
            for ($i = 0; $i < 3; $i++) {
                if ($facetNormal["vertex"][$i][0] == $highestVertexX) {
                    $skip = true;
                }
            }
            if (!$skip) {
                $facetNormals[] = $facetNormal;
            }
        }

        $contentArray["facets"] = $facetNormals;

        $this->setStlFileContentArray($contentArray);

        return $this;
    }

    /**
     * Highest possible Y coordinate of a vertex.
     *
     * @return float
     */
    public function getHighestVertexY() : float
    {
        $max = null;
        foreach ($this->getStlFileContentArray()["facets"] as $facetNormal) {
            for ($i = 0; $i < 3; $i++) {
                if (is_null($max)) {
                    $max = $facetNormal["vertex"][$i][1];
                    continue;
                }
                if ($max < $facetNormal["vertex"][$i][1]) {
                    $max = $facetNormal["vertex"][$i][1];
                }
            }
        }

        return $max;
    }

    /**
     * Remove all vertices with the highest Y values.
     *
     * @return STLMillingEdit
     */
    public function removeHighestYVertices() : STLMillingEdit
    {
        $facetNormals = [];
        $contentArray = $this->getStlFileContentArray();
        $highestVertexY = $this->getHighestVertexY();

        foreach ($contentArray["facets"] as $facetNormal) {
            $skip = false;
            if ($facetNormal["vertex"][0][1] == $highestVertexY &&
                $facetNormal["vertex"][1][1] == $highestVertexY &&
                $facetNormal["vertex"][2][1] == $highestVertexY
            ) {
                $skip = true;
            }
            if (!$skip) {
                $facetNormals[] = $facetNormal;
            }
        }

        $contentArray["facets"] = $facetNormals;

        $this->setStlFileContentArray($contentArray);

        return $this;
    }


    /**
     * Remove all vertices with the lowest Y values.
     *
     * @return STLMillingEdit
     */
    public function removeLowestYVertices() : STLMillingEdit
    {
        $facetNormals = [];
        $contentArray = $this->getStlFileContentArray();
        $lowestVertexY = $this->getLowestVertexY();

        foreach ($contentArray["facets"] as $facetNormal) {
            $skip = false;
            if ($facetNormal["vertex"][0][1] == $lowestVertexY &&
                $facetNormal["vertex"][1][1] == $lowestVertexY &&
                $facetNormal["vertex"][2][1] == $lowestVertexY
            ) {
                $skip = true;
            }
            if (!$skip) {
                $facetNormals[] = $facetNormal;
            }
        }

        $contentArray["facets"] = $facetNormals;

        $this->setStlFileContentArray($contentArray);

        return $this;
    }

    /**
     * Lowest possible Y coordinate of a vertex.
     *
     * @return float
     */
    public function getLowestVertexY() : float
    {
        $min = null;
        foreach ($this->getStlFileContentArray()["facets"] as $facetNormal) {
            for ($i = 0; $i < 3; $i++) {
                if (is_null($min)) {
                    $min = $facetNormal["vertex"][$i][1];
                    continue;
                }
                if ($min > $facetNormal["vertex"][$i][1]) {
                    $min = $facetNormal["vertex"][$i][1];
                }
            }
        }

        return $min;
    }


    /**
     * Highest possible Z coordinate of a vertex.
     *
     * @return float
     */
    public function getHighestVertexZ() : float
    {
        $max = null;
        foreach ($this->getStlFileContentArray()["facets"] as $facetNormal) {
            for ($i = 0; $i < 3; $i++) {
                if (is_null($max)) {
                    $max = $facetNormal["vertex"][$i][2];
                    continue;
                }
                if ($max < $facetNormal["vertex"][$i][2]) {
                    $max = $facetNormal["vertex"][$i][2];
                }
            }
        }

        return $max;
    }

    /**
     * Remove all vertices with the highest Z values.
     *
     * @return STLMillingEdit
     */
    public function removeHighestZVertices() : STLMillingEdit
    {
        $facetNormals = [];
        $contentArray = $this->getStlFileContentArray();
        $highestVertexZ = $this->getHighestVertexZ();

        foreach ($contentArray["facets"] as $facetNormal) {
            $skip = false;
            if ($facetNormal["vertex"][0][2] == $highestVertexZ &&
                $facetNormal["vertex"][1][2] == $highestVertexZ &&
                $facetNormal["vertex"][2][2] == $highestVertexZ
            ) {
                $skip = true;
            }

            if (!$skip) {
                $facetNormals[] = $facetNormal;
            }
        }

        $contentArray["facets"] = $facetNormals;

        $this->setStlFileContentArray($contentArray);

        return $this;
    }


    /**
     * Remove all vertices with the lowest Z values.
     *
     * @return STLMillingEdit
     */
    public function removeLowestZVertices() : STLMillingEdit
    {
        $facetNormals = [];
        $contentArray = $this->getStlFileContentArray();
        $lowestVertexZ = $this->getLowestVertexZ();

        foreach ($contentArray["facets"] as $facetNormal) {
            $skip = false;
            for ($i = 0; $i < 3; $i++) {
                if ($facetNormal["vertex"][0][2] == $lowestVertexZ &&
                    $facetNormal["vertex"][1][2] == $lowestVertexZ &&
                    $facetNormal["vertex"][2][2] == $lowestVertexZ
                ) {
                    $skip = true;
                }
            }
            if (!$skip) {
                $facetNormals[] = $facetNormal;
            }
        }

        $contentArray["facets"] = $facetNormals;

        $this->setStlFileContentArray($contentArray);

        return $this;
    }

    /**
     * Lowest possible Z coordinate of a vertex.
     *
     * @return float
     */
    public function getLowestVertexZ() : float
    {
        $min = null;
        foreach ($this->getStlFileContentArray()["facets"] as $facetNormal) {
            for ($i = 0; $i < 3; $i++) {
                if (is_null($min)) {
                    $min = $facetNormal["vertex"][$i][2];
                    continue;
                }
                if ($min > $facetNormal["vertex"][$i][2]) {
                    $min = $facetNormal["vertex"][$i][2];
                }
            }
        }

        return $min;
    }


    /**
     * Extract the actual milling objects.
     *
     * @return STLMillingEdit
     */
    public function extractMillingContent() : STLMillingEdit
    {
        try {
            $this->removeHighestXVertices()
                ->removeLowestXVertices()
                ->removeHighestYVertices()
                ->removeLowestYVertices()
                ->removeHighestZVertices()
                ->removeLowestZVertices();
        } catch (\TypeError $ex) {
            // Silently ignore an object without remaining facets. This is normally detected by
            // not being able to find a lower / higher coordinate.
        }
        return $this;
    }
}