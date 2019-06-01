<?php
$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->in('tests');

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        '@DoctrineAnnotation' => true,
        'multiline_whitespace_before_semicolons' => array(
            'strategy' => 'no_multi_line',
        ),
        'array_syntax' => array(
            'syntax' => 'long',
        ),
        'ordered_class_elements' => false,
        'protected_to_private' => false,
        'declare_strict_types' => false,
        'yoda_style' => null,
    ])
    ->setFinder($finder);
