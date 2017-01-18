<?php

$dirs = array_filter(glob('*'), 'is_dir');

foreach ($dirs as $directory) {
    echo $directory . PHP_EOL;

    if (hasListenFile($directory)) {
        $listens = listeners($directory);

        foreach ($listens as $event => $listeners) {
            echo $event . ':' . PHP_EOL;
            foreach ($listeners as $listener) {
                echo  '  '.$listener . PHP_EOL;
            }
        }
    }
}


function listeners($directory)
{
    return include $directory . DIRECTORY_SEPARATOR . 'events.php';
}

function hasListenFile($directory)
{
    return file_exists($directory . DIRECTORY_SEPARATOR . 'events.php');
}
