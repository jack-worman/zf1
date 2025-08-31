<?php

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = (new PhpCsFixer\Finder())
    ->in([__DIR__.'/packages', __DIR__.'/tests'])
    ->notPath('~/_.*?/~');

return (new PhpCsFixer\Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        '@Symfony' => true,
        'php_unit_method_casing' => false,
    ])
    ->setFinder($finder);
