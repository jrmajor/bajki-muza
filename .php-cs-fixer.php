<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->append(['artisan'])
    ->notPath('bootstrap/cache')
    ->notPath('node_modules')
    ->notPath('storage')
    ->notName('*.blade.php')
    ->notName('_ide_helper*.php')
    ->ignoreVCSIgnored(true);

return Major\CS\config($finder, [
    'no_null_property_initialization' => false,
])->setCacheFile('.cache/.php-cs-fixer.cache');
