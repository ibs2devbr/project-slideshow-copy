<?php

    require_once ('_a_function.php');
    
    defineVariableCreator ([ 'define' ]);

    function setMetaRefresh (string $is_input = ''): string {
        return implode ('', [ '<meta http-equiv=\'refresh\' content=\'0; url=', $is_input, '\'>' ]);
    };

    function getFileDateTime (string $is_input = ''): object {
        $is_format = 'd/m/Y H:i:s';
        return DateTime::createFromFormat ($is_format, date ($is_format, filemtime ($is_input)));
    };

?>