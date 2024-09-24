<?php

function printArrayInChunks($array, $batchSize) { //This defines a function printArrayInChunks which takes two parameters
    $batches = array_chunk($array, $batchSize); //This is a built-in PHP function that splits an array into chunks.
    foreach ($batches as $batchIndex => $batch) { //loop iterates over each batch of the chunked array
        foreach ($batch as $index => $value) { //loop iterates over each element of the current chunk
            echo "batch " . $batchIndex . " item " . $index . " - value: " . $value . "\n"; //This prints out the batch number, item number within that batch, and the value of the current item, followed by a newline
        }
    }
}

$array = range(1, 20); //creates an array of numbers from 1 to 20.
$batchSize = 5; //array will be chunked into groups of 5 elements.
printArrayInChunks($array, $batchSize); //This calls the printArrayInChunks function with the created array and batch size.
