<?php

$ds = DIRECTORY_SEPARATOR;
$basePath = realpath(__DIR__."$ds..");

return [
    'Zend_Loader_StandardAutoloaderTest' => $basePath.$ds.'StandardAutoloaderTest.php',
    'Zend_Loader_ClassMapAutoloaderTest' => $basePath.$ds.'ClassMapAutoloaderTest.php',
];
