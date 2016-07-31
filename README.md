# php3d/stlslice #

[![Build Status](https://travis-ci.org/fgheorghe/stlslice.svg?branch=master)](https://travis-ci.org/fgheorghe/stlslice)

## Synopsis

This library provides PHP 7 functionality for slicing STL formatted 3D objects, and converting them to SVG or GCODE.

NOTE: GCode conversion is highly experimental - change to suit your needs.

NOTE: Requires bcscale(16);

## Set-up

Add this to your composer.json file:

```javascript
  [...]
  "require": {
      [...]
      "php3d/stlslice": "1.*"
  }
```

Then run composer:

```bash
composer.phar install
```

## Examples

Extract layers:

```PHP
$layers = (new \php3d\stlslice\STLSlice($stl, 10))->slice();
```

Convert to SVG:

```PHP
echo (new \php3d\stlslice\Examples\STL2Svg($layers))->toSvgString();
```

Convert to GCode (milling machine):

```PHP
echo (new \php3d\stlslice\Examples\STL2GCode($layers, 100))->toGCodeString();
```