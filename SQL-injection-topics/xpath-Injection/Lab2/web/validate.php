<?php
function validate($input) {
    if (filter_var($input, FILTER_VALIDATE_REGEXP, [
        'options' => [
//            'regexp' => '/union|select|insert|update|delete|is/i'
              'regexp' => '/union|insert|update|delete/is'
            ]
    ])) {
        die('Invalid input');
    }
    // return str_replace(
    //     ['--', '#', '/*', '*/'],
    //     '',
    //     $input
    // );
    return $input;
}
?>
