<?php

$basePath = dirname(__DIR__);

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in($basePath . '/src')
    ->in($basePath . '/tests')
    ->in($basePath . '/config');

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::NONE_LEVEL)
    ->fixers([
        'encoding','short_tag','braces','elseif','eof_ending',
        'function_call_space','function_declaration','indentation',
        'line_after_namespace','linefeed','lowercase_constants',
        'lowercase_keywords','method_argument_space','multiple_use',
        'parenthesis','php_closing_tag','trailing_spaces','visibility',
        'duplicate_semicolon','extra_empty_lines',
        'multiline_array_trailing_comma','new_with_braces','object_operator',
        'operators_spaces','remove_lines_between_uses','return',
        'single_array_no_trailing_comma','spaces_before_semicolon',
        'spaces_cast','standardize_not_equal','ternary_spaces',
        'whitespacy_lines','concat_with_spaces',
        'multiline_spaces_before_semicolon','short_array_syntax'
    ])
    ->finder($finder);
