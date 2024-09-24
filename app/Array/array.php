<?php

function printArrayInChunks($array, $batchSize) {
    $batches = array_chunk($array, $batchSize);
    foreach ($batches as $batchIndex => $batch) {
        foreach ($batch as $index => $value) {
            echo "batch " . $batchIndex . " item " . $index . " - value: " . $value . "\n";
        }
    }
}


$array = range(1, 20);
$batchSize = 5;
printArrayInChunks($array, $batchSize);
