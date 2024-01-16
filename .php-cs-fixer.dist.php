<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->ignoreDotFiles(false)
    ->notPath([
        'tests/Zend/Loader/_files/ParseError.php',
        'tests/Zend/Loader/_files/AutoloaderClosure.php',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder);
