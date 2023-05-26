<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
                ->in([
                    __DIR__ . "/source"
                ])
                ->exclude([
                    "vendor"
                ])
                ->name('*.php')
                ->ignoreDotFiles(true)
                ->ignoreVCS(true);

$config = new Config();

return $config->setRules([
    '@PSR12' => true,
    'no_unused_imports' => true

    // Add additional rules alongside the preset
    // 'strict_param' => true,
    // 'array_syntax' => [ 'syntax' => 'short' ],

    // Remove rules defined in preset
    // 'full_opening_tag' => false,
])->setFinder($finder);
