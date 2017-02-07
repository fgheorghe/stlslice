<?php
require "vendor/autoload.php";

use php3d\stlslice\Examples\STL2GCode;
use php3d\stlslice\Examples\STLMillingEdit;
use php3d\stlslice\STLSlice;
use php3d\stl\STL;

ini_set('memory_limit', -1);

if (count($argv) !== 3) {
    die("Usage: php " . $argv[0] . " file-path output-path\n");
}

$fileName = $argv[1];

try {
    bcscale(16);

    echo "[+] Reading STL file...\n";
    $stl = STL::fromString(file_get_contents($fileName));

    echo "[+] Removing edges...\n";
    $mill = STL::fromArray((new STLMillingEdit($stl->toArray()))
        ->extractMillingContent()
        ->getStlFileContentArray()
    );

    echo "[+] Slicing objects...\n";
    $layers = (new STLSlice($mill, 10))
        ->slice();

    echo "[+] Generating GCode...\n";
    file_put_contents($argv[2], (new STL2GCode($layers, 10))->toGCodeString());

    echo "[++] Done.\n";
} catch (Exception $ex) {
    die("[-] Can not convert file: " . $ex->getMessage());
}

