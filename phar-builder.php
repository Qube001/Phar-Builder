<?php

$pharName = $argv[1] ?? null;
$folderPath = $argv[2] ?? null;
$path4Require = $argv[3] ?? null;

if($pharName == null or $folderPath == null or $path4Require == null) {
    echo "Cant build PHAR, missing components" . PHP_EOL;
    return false;
}

function build(string $pharName, string $folderPath, string $path4Require) {
    $timestarted = microtime(true);
    try {
        $bootstrap = explode("/", $path4Require);
        $bootstrap = $bootstrap[count($bootstrap)-1];
        echo $bootstrap;
        $phar = new Phar($pharName.".phar", 0);
        $phar->setMetaData(["bootstrap"=> $bootstrap]);
        $phar->setStub(
        '<?php require("phar://" . __FILE__ . "/'.$path4Require.'"); __HALT_COMPILER();'
        );
        $phar->startBuffering();
        
        $directory = new \RecursiveDirectoryIterator($folderPath, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory);
        $count = count($phar->buildFromIterator($iterator, $folderPath));
        
        $phar->stopBuffering();
        $timetooked = round(microtime(true) - $timestarted, 3) ." Seconds";
    } catch(Exception $e) {
        echo "",$e;
    }
}

build($pharName, $folderPath, $path4Require);
