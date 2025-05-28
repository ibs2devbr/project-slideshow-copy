<?php

    foreach ([ 'config', 'define' ] as $is_archive):
        foreach (getFileArray ([ 'search' => $is_archive ]) as $is_index):
            $is_index = pathinfo ($is_index);
            define (setTargetName (explode ('-', $is_index['filename'])), setJson2Array ($is_index['basename']));
        endforeach;
    endforeach;

    function isKeyExist (array $is_input = [], string $is_key = ''): bool {
        if (!isset ($is_input)) return false;
        if (!array_key_exists ($is_key, $is_input)) return false;
        return true;
    };

    function isKeyTrue (array $is_input = [], string $is_key = ''): bool {
        if (!isKeyExist ($is_input, $is_key)) return false;
        if (!isTrue ($is_input[$is_key])) return false;
        return true;
    };

    function isTrue (array|string|object $is_input = ''): bool {
        if (!isset ($is_input)) return false;
        if (empty ($is_input)) return false;
        return true;
    };

    function setArray (array|string $is_input = []): bool|array {
        return array_values (array_filter ([
            ...isString ($is_input) ? [ $is_input ] : [],
            ...isArray ($is_input) ? [ ...array_is_list ($is_input) ? $is_input : [] ] : [],
        ], function ($is_index) {
            if (isString ($is_index))
                return $is_index;
        }));
    };

    function setObjectVar ($is_input = []) {
        return is_object ($is_input) ? get_object_vars ($is_input) : $is_input;
    };

    function isArray (mixed $is_input = []): bool {
        $is_input = setObjectVar ($is_input);
        return isTrue ($is_input) && is_array ($is_input);
    };

    function isString (mixed $is_input = ''): bool {
        return isTrue ($is_input) && is_string ($is_input);
    };

    function getKeyValue (array $is_input = [], string $is_key = '', array|float|string $is_backup = []): array|string {
        $is_input = setObjectVar ($is_input);
        if (isKeyTrue ($is_input, $is_key))
            if (isTrue (setObjectVar ($is_input[$is_key])))
                return setObjectVar ($is_input[$is_key]);
        return $is_backup;
    };

    function setProper (array $is_input = [], array|string $is_key = '', array|float|string $is_backup = []): array {
        $is_result = [];
        if (isArray ($is_key)):
            foreach ($is_key as $is_index):
                $is_result[$is_index] = getKeyValue ($is_input, $is_index, $is_backup);
            endforeach;
        endif;
        if (isString ($is_key)):
            $is_result[$is_key] = getKeyValue ($is_input, $is_key, $is_backup);
        endif;
        return $is_result;
    };

    function setFileName (array|string $is_input = ''): string {
        $is_input = implode ('-', setArray ($is_input));
        if (isKeyTrue (pathinfo ($is_input), 'extension')) $is_input = pathinfo ($is_input)['filename'];
        $is_input = setTonicSyllable ($is_input);
        $is_input = preg_replace ('/[^0-9a-zA-Z_]/i', '-', $is_input);
        $is_input = preg_replace ('/-+/', '-', $is_input);
        return strtolower (trim ($is_input, '-'));
    };

    function setTargetName (array|string $is_input = ''): string {
        $is_input = implode (' ', setArray ($is_input));
        $is_input = setTonicSyllable ($is_input);
        $is_input = preg_replace ('/[^0-9a-zA-Z]/i', ' ', $is_input);
        $is_input = preg_replace ('/\s+/', ' ', $is_input);
        $is_input = explode (' ', trim ($is_input));
        return implode ('', array_map (function ($i, $k) { return !$k ? strtolower ($i) : ucfirst ($i); }, $is_input, array_keys ($is_input)));
    };

    function setTonicSyllable (string $is_input = ''): string {
        foreach ([
            'A' => '/(' . implode ('|', [ 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å' ]) . ')/',
            'E' => '/(' . implode ('|', [ 'È', 'É', 'Ê', 'Ë' ]) . ')/',
            'I' => '/(' . implode ('|', [ 'Ì', 'Í', 'Î', 'Ï' ]) . ')/',
            'O' => '/(' . implode ('|', [ 'Ò', 'Ó', 'Ô', 'Õ' ]) . ')/',
            'U' => '/(' . implode ('|', [ 'Ù', 'Ú', 'Û', 'Ü' ]) . ')/',
            'C' => '/(' . implode ('|', [ 'Ç' ]) . ')/',
            'N' => '/(' . implode ('|', [ 'Ñ' ]) . ')/',
            'Y' => '/(' . implode ('|', [ 'Ÿ' ]) . ')/',
            'a' => '/(' . implode ('|', [ 'à', 'á', 'â', 'ã', 'ä', 'å' ]) . ')/',
            'e' => '/(' . implode ('|', [ 'è', 'é', 'ê', 'ë' ]) . ')/',
            'i' => '/(' . implode ('|', [ 'ì', 'í', 'î', 'ï' ]) . ')/',
            'o' => '/(' . implode ('|', [ 'ò', 'ó', 'ô', 'õ' ]) . ')/',
            'u' => '/(' . implode ('|', [ 'ù', 'ú', 'û', 'ü' ]) . ')/',
            'c' => '/(' . implode ('|', [ 'ç' ]) . ')/',
            'n' => '/(' . implode ('|', [ 'ñ' ]) . ')/',
            'y' => '/(' . implode ('|', [ 'ÿ' ]) . ')/',
        ] as $is_replace => $is_pattern)
            $is_input = preg_replace ($is_pattern, $is_replace, $is_input);
        return trim ($is_input);
    };

    function setDir (string $is_input = ''): string {
        $is_result = implode ('/', [ '.', setFileName ($is_input) ]);
        if (!is_dir ($is_result))
            mkdir ($is_result, 0777, true);
        return $is_result;
    };

    function getFileContent (string $is_input = ''): string {
        return isFileExist ($is_input) ? file_get_contents (isFileExist ($is_input)) : '';
    };

    function isPathExist (string $is_input = ''): string {
        return file_exists (setPath ($is_input)) ? setPath ($is_input) : '';
    };

    function isFileExist (string $is_input = ''): string {
        if (isKeyTrue (pathinfo ($is_input), 'extension')):
            $is_input = implode ('.', [ setFileName (pathinfo ($is_input)['filename']), pathinfo ($is_input)['extension'] ]);
        else:
            $is_input = implode ('.', [ setFileName ($is_input), 'json' ]);
        endif;
        return isPathExist ($is_input);
    };

    function setProperFileArray (array $is_input = []): array {
        return [
            ...setProper ($is_input, 'dir', 'json'),
            ...setProper ($is_input, 'search'),
        ];
    };

    function getPathArray (array $is_input = []): array {
        $is_proper = setProperFileArray ($is_input);
        return array_map (function ($is_index) { return isPathExist ($is_index); }, getFileArray ([ 'dir' => $is_proper['dir'], 'search' => $is_proper['search'] ]));
    };

    function getFileArray (array $is_input = []): array {
        $is_proper = setProperFileArray ($is_input);
        return array_values (array_filter (scandir (setDir ($is_proper['dir'])), function ($is_index) use ($is_proper) {
            if (isKeyTrue (pathinfo ($is_index), 'extension'))
                if (isTrue (pathinfo ($is_index)['extension'] === $is_proper['dir']))
                    if (!in_array (substr ($is_index, 0, 1), [ '_' ]))
                        if (isString ($is_proper['search'])):
                            if (str_contains ($is_index, $is_proper['search'])):
                                if (getFileContent ($is_index)):
                                    return $is_index;
                                endif;
                            endif;
                        elseif (getFileContent ($is_index)):
                            return $is_index;
                        endif;
        }));
    };

    function setObject2Array (array|object $is_input = []): array {
        $is_result = [];
        foreach ($is_input as $is_key => $is_value):
            if (is_object ($is_value)): $is_result[$is_key] = setObject2Array (get_object_vars ($is_value));
            elseif (is_array ($is_value)): $is_result[$is_key] = setObject2Array ($is_value);
            else: $is_result[$is_key] = $is_value; endif;
        endforeach;
        return $is_result;
    };

    function setJson2Array (string $is_input = ''): array {
        return getFileContent ($is_input) ? setObject2Array (json_decode (getFileContent ($is_input))) : [];
    };

    function setPath (string $is_input = ''): string {
        if (isKeyTrue (pathinfo ($is_input), 'extension')):
            $is_dir = setDir (pathinfo ($is_input)['extension']);
            $is_filename = implode ('.', [ setFileName (pathinfo ($is_input)['filename']), setFileName (pathinfo ($is_input)['extension']) ]);
            return implode ('/', [ $is_dir, $is_filename ]);
        endif;
        return '';
    };

    function setMetaRefresh (string $is_input = ''): string {
        return implode ('', [ '<meta http-equiv=\'refresh\' content=\'0; url=', $is_input, '\'>' ]);
    };

    function getFileDateTime (string $is_input = ''): object {
        $is_format = 'd/m/Y H:i:s';
        return DateTime::createFromFormat ($is_format, date ($is_format, filemtime ($is_input)));
    };

?>