<?php

    foreach ([
        'catalog', 
        'config',
        'define',
    ] as $is_archive):
        foreach (getFileArray ([ 'search' => $is_archive ]) as $is_index):
            $is_index = pathinfo ($is_index);
            define (setTargetName (explode ('-', $is_index['filename'])), setJson2Array ($is_index['basename']));
        endforeach;
    endforeach;

    function getStyle (string $is_key = '', float|string $is_input = ''): array {
        $is_result = [
            'text-shadow' => [ 'text-shadow' => '0 1.5px 3px rgba(0, 0, 0, .75)' ],
            'background-image' => [
                ...file_exists ($is_input) ? [
                    'background-attachment' => 'scroll',
                    'background-image' => 'url(' . $is_input . ')',
                    'background-position' => 'center',
                    'background-repeat' => 'no-repeat',
                    'background-size' => 'cover',
                ] : [
                ],
            ],
            'circle-size' => [ 'border-radius' => '50%', 'height' => $is_input, 'width' => $is_input ],
            'center-position' => [ 'left' => '50%', 'position' => 'absolute', 'top' => '50%', 'transform' => 'translate(-50%, -50%)' ],
            'between-line' => [
                'background-color' => 'rgba(0, 0, 0, .1)',
                'border-width' => '1px 0',
                'border' => 'solid rgba(0, 0, 0, .15)',
                'box-shadow' => 'inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15)',
                'height' => getNumber ($is_input) . 'rem',
                'width' => '100%',
            ],
            'display-flex' => [
                'align-items' => 'center',
                'display' => 'flex',
                'justify-content' => inArray ($is_input, [ 'start', 'end', 'center', 'space-between', 'space-around', 'space-evenly' ], 'center'),
            ],
            'filter-blur' => [
                '-webkit-backdrop-filter' => 'blur(' . (in_array ($is_input, setDecimalRange (100)) ? $is_input : 5) . 'px)',
                'backdrop-filter' => 'blur(' . (in_array ($is_input, setDecimalRange (100)) ? $is_input : 5) . 'px)',
            ],
        ];
        if (isKeyTrue ($is_result, $is_key)) return $is_result[$is_key];
        return [];
    };

    function getDefineKeyword (): array {
        $is_result = [];
        $is_i_array = configKeyword[0];
        $is_j_array = isArray (configKeyword[1]) ? configKeyword[1] : defineGenero;
        $is_k_array = configKeyword[2];
        for ($i = 0; $i < sizeof ($is_i_array); $i++):
            for ($j = 0; $j < sizeof ($is_j_array); $j++):
                for ($k = 0; $k < sizeof ($is_k_array); $k++):
                    $is_index = implode (' ', [ $is_i_array[$i], $is_j_array[$j], $is_k_array[$k] ]);
                    array_push ($is_result, setCamelcase ($is_index));
                endfor;
            endfor;
        endfor;
        shuffle ($is_result);
        return $is_result;
    };

    define ('configKeywordArray', getDefineKeyword ());

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

    function isURL (string $is_input = ''): bool {
        return preg_match ('/^(https?|ftp|file):\/\/(www\.)?/', $is_input);
    };

    function setStyleArray (): array {
        $is_input = [ ...defineStyle, ...getPathArray ([ 'dir' => 'css' ]) ];
        if (isArray (setArray ($is_input))):
            return array_map (function ($is_index) {
                return implode ('', [ '<link', ' href=\'', $is_index, '\'', ' rel=\'stylesheet\'', ' crossorigin=\'anonymous\'', '>' ]);
            }, setArray ($is_input));
        endif;
        return [];
    };

    function setScriptArray (): array {
        $is_input = [ ...defineScript, ...getPathArray ([ 'dir' => 'js' ]) ];
        if (isArray (setArray ($is_input))):
            return array_map (function ($is_index) {
                return implode ('', [ '<script', ' src=\'', $is_index, '\'', ' crossorigin=\'anonymous\'', ...!isURL ($is_index) ? [ ' type=\'module\'' ] : [], '></script>' ]);
            }, setArray ($is_input));
        endif;
        return [];
    };

    function setAttrib (array|string $is_input = '', string $is_attrib = 'id'): array {
        $is_true = in_array ($is_attrib, [ 'class', 'style' ]);
        return isArray (setArray ($is_input)) ? [ ' ', setFileName ($is_attrib), '=\'', implode ($is_true ? ' ' : '', setArray ($is_input)), '\'' ] : [];
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

    function setCamelcase (array|string $is_input = ''): string {
        $is_input = implode (' ', setArray ($is_input));
        $is_input = preg_replace ('/\s+/', ' ', $is_input);
        return implode (' ', array_map (function ($i) { return in_array ($i, defineLowercase) ? strtolower ($i) : ucfirst ($i); }, explode (' ', trim ($is_input))));
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

    function setSELETOR (array $is_input = [], array $is_proper = []): array {
        $is_proper = setProper ($is_proper, [ 'id', 'style', 'wrap' ]);
        $is_wrap = in_array ($is_proper['wrap'], defineSeletor) ? $is_proper['wrap'] : 'div';
        return isArray ($is_input) ? [
            '<',
                $is_wrap,
                ...setAttrib ($is_proper['id']),
                ...setClass (getClass (in_array ($is_wrap, array_keys (definePool)) ? $is_wrap : 'wrapColumn')),
                ...setStyle ($is_proper['style']),
            '>',
                ...$is_input,
            '</',
                $is_wrap,
            '>',
        ] : [
        ];
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
        $is_array = [];
        foreach (array_diff (scandir (setDir ($is_proper['dir'])), [ '.', '..' ]) as $is_index)
            if (isTrue (pathinfo ($is_index)['extension'] === $is_proper['dir']))
                if (!in_array (substr ($is_index, 0, 1), [ '_' ]))
                    if (isString ($is_proper['search'])):
                        if (str_contains ($is_index, $is_proper['search'])):
                            if (getFileContent ($is_index)):
                                $is_array[] = implode ('/', [ '.', $is_proper['dir'], $is_index ]);
                                // $is_array[] = $is_index;
                            endif;
                        endif;
                    elseif (getFileContent ($is_index)):
                        $is_array[] = implode ('/', [ '.', $is_proper['dir'], $is_index ]);
                        // $is_array[] = $is_index;
                    endif;
        return $is_array;
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

    function getClass (string $is_input = ''): string {
        if (isKeyTrue (definePool, $is_input)):
            $is_array = definePool[$is_input];
            sort ($is_array);
            return implode (' ', $is_array);
        endif;
        return '';
    };

    function setClass (array|string $is_input = []): array {
        $is_input = implode (' ', setArray ($is_input));
        $is_input = explode (' ', $is_input);
        $is_input = array_unique ($is_input);
        sort ($is_input);
        $is_input = array_values ($is_input);
        return setAttrib ($is_input, 'class');
    };

    function setStyle (array $is_input = []): array {
        if (!array_is_list ($is_input))
            return setAttrib (implode (' ', array_map (function ($is_index) use ($is_input) {
                return implode ('', [ $is_index, ': ', $is_input[$is_index], ';' ]);
            }, array_keys ($is_input))), 'style');
        return [];
    };

    function setHeadTitle (): array {
        $is_array = array_filter (array_map (function ($is_index) {
            if (isKeyTrue (configDescription, $is_index))
                    return setCamelcase (configDescription[$is_index]);
        }, [ 'title', 'subtitle', 'description' ]));
        return [ '<title>', ...isArray ($is_array) ? [ implode (' | ', $is_array) ] : [], '</title>' ];
    };

    function setMetaWrapper (array $is_input = []): array {
        return isArray (setArray ($is_input)) ? [ '<meta', ...setArray ($is_input), '>' ] : [];
    };

    function setMetaDescription (): array {
        return array_filter (array_map (function ($is_index) {
            if (isKeyTrue (configDescription, $is_index)):
                $is_name = [ ...in_array ($is_index, [ 'title' ]) ? [ 'author' ] : [], ...in_array ($is_index, [ 'subtitle' ]) ? [ 'description' ] : [] ];
                return implode ('', setMetaWrapper ([ ...setAttrib ($is_name, 'name'), ...setAttrib (configDescription[$is_index], 'content') ]));
            endif;
        }, [ 'title', 'subtitle' ]));
    };

    function setMetaKeyword (): array {
        return isArray (configKeywordArray) ? setMetaWrapper ([ ...setAttrib ('keywords', 'name'), ...setAttrib (implode (', ', configKeywordArray), 'content') ]) : [];
    };

    function setMetaViewport (): array {
        $is_input = [ 'width=device-width', 'initial-scale=1', 'shrink-to-fit=no' ];
        return setMetaWrapper ([ ...setAttrib ('viewport', 'name'), ...setAttrib (implode (', ', $is_input), 'content') ]);
    };

    /*
    *
    *
    *
    *
    *
    */

    function getNumber (string $is_input = ''): float {
        $is_pattern = '/^';
            $is_pattern .= '[+-]?';
            $is_pattern .= '\d+';
            $is_pattern .= '(\.\d+)?';
        $is_pattern .= '$/';
        return floatval (preg_replace ($is_pattern, '', $is_input));
    };

    function setDecimalRange (float $is_end = 100): array {
        $is_array = [];
        for ($i = 0; $i <= $is_end; $i += .1) $is_array[] = round ($i, 2);
        return $is_array;
    };

    function setHexInvert (string $is_input = ''): bool|string {
        $is_input = ltrim ($is_input, '#');
        if (!preg_match ('/^[0-9a-fA-F]{3}$/', $is_input) && !preg_match ('/^[0-9a-fA-F]{6}$/', $is_input)) return false;
        $is_len = strlen ($is_input);
        $is_new_color = '';
        if ($is_len === 3):
            for ($i = 0; $i < $is_len; $i++):
                $is_hex = str_repeat ($is_input[$i], 2);
                $is_decimal = hexdec ($is_hex);
                $is_invert = 255 - $is_decimal;
                $is_new_color .= str_pad (dechex ($is_invert), 2, '0', STR_PAD_LEFT);
            endfor;
        else:
            for ($i = 0; $i < $is_len; $i += 2):
                $is_hex = substr ($is_input, $i, 2);
                $is_decimal = hexdec ($is_hex);
                $is_invert = 255 - $is_decimal;
                $is_new_color .= str_pad (dechex ($is_invert), 2, '0', STR_PAD_LEFT);
            endfor;
        endif;
        return strtoupper (implode ('', [ '#', $is_new_color ]));
    };

    function isValidHex (string $is_input = ''): bool {
        $is_input = ltrim ($is_input, '#');
        return preg_match ('/^[0-9a-fA-F]{3}$/', $is_input) || preg_match ('/^[0-9a-fA-F]{6}$/', $is_input);
    };

    function inArray (string $is_input = '', array|string $is_array = '', array|string $is_return = ''): array|string {
        return in_array ($is_input, setArray ($is_array)) ? $is_input : $is_return;
    };

    function getINDEX (array $is_input = [], string $is_key = 'container'): array {
        $is_array = [];
        if (isArray ($is_input))
            for ($i = 0; $i < sizeof ($is_input); $i++)
                if (isKeyTrue ($is_input[$i], $is_key))
                    for ($j = 0; $j < sizeof ($is_input[$i][$is_key]); $j++)
                        if (isKeyTrue ($is_input[$i][$is_key][$j], $is_key))
                            for ($k = 0; $k < sizeof ($is_input[$i][$is_key][$j][$is_key]); $k++)
                                array_push ($is_array, $is_input[$i][$is_key][$j][$is_key][$k]);
        shuffle ($is_array);
        return $is_array;
    };

    function getPictureRandom (array|string $is_input = ''): string {
        return getRandom (hasValidPath (setArray ($is_input)));
    };

    function hasValidPath (array|string $is_input = ''): array {
        return isArray (setArray ($is_input)) ? array_values (array_map (function ($is_index) {
            if (isPathExist ($is_index)) return isPathExist ($is_index);
            if (isURLExist ($is_index)) return isURLExist ($is_index);
        }, setArray ($is_input))) : [];
    };

    function isKeyHasValidPath (array $is_input = [], string $is_key = ''): array {
        return isKeyTrue ($is_input, $is_key) ? hasValidPath ($is_input[$is_key]) : [];
    };

    function isURLMPago (string $is_input = ''): bool {
        return preg_match ('/^https:\/\/mpago\.la\/[a-zA-Z0-9]+$/', $is_input);
    };

    function isURLExist (string $is_input = ''): bool|string {
        if (!isURL ($is_input)) return false;
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_URL, $is_input);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
        $html = curl_exec ($ch);
        $is_http = curl_getinfo ($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        return $is_http === 200 ? $is_input : false;
    };

    function getRandom (array $is_input = []): string {
        return $is_input[array_rand ($is_input)];
    };

    function setSlideShow (string $is_input = 'jpg'): array {
        $is_set = 'slide';
        $is_array = getINDEX (catalogContent, 'container');
        // $is_array = getFileArray ([ 'dir' => $is_input ]);
        $is_proper = configSlideshow;
        $is_proper['arrow']['position'] = inArray ($is_proper['arrow']['position'], [ 'bottom', 'middle', 'top' ], 'middle');
        $is_proper['theme']['type'] = inArray ($is_proper['theme']['type'], [ 'photo', 'slide' ], 'photo');
        if (getNumber ($is_proper['arrow']['font-size']) > getNumber ($is_proper['arrow']['size']))
            $is_proper['arrow']['font-size'] = $is_proper['arrow']['size'];
        if (getNumber ($is_proper['dot']['border']['size']) > getNumber ($is_proper['theme']['margin']))
            $is_proper['dot']['border']['size'] = $is_proper['theme']['margin'];
        $is_proper['arrow']['bg']['color'] = isValidHex ($is_proper['arrow']['bg']['color']) ? $is_proper['arrow']['bg']['color'] : '#fff';
        return [
            '<div',
                ...setClass ([ setFileName ([ $is_set, 'wrapper' ]) ]),
                ...setStyle ([
                    'display' => 'flex',
                    'height' => '100%',
                    'justify-content' => 'center',
                    'position' => 'relative',
                    'width' => '100%',
                ]),
            '>',
                ...array_map (function ($i, $k) use ($is_proper, $is_set) {
                    // $is_index = $i;
                    $is_index = getPictureRandom (isKeyHasValidPath ($i, 'gallery'));
                    return implode ('', [
                        '<div',
                            ...setClass ([ setFileName ([ $is_set, 'content' ]) ]),
                            ...setStyle ([
                                ...getStyle ('background-image', $is_index),
                                'display' => !$k ? 'flex' : 'none',
                                'height' => '100%',
                                'justify-content' => 'center',
                                'left' => 0,
                                'opacity' => !$k ? 1 : 0,
                                'position' => 'absolute',
                                'top' => 0,
                                'transition' => $is_proper['theme']['ease'],
                                'width' => '100%',
                                'z-index' => 1,
                            ]),
                        '>',
                            ...in_array ($is_proper['theme']['type'], [ 'photo' ]) ? [
                                '<img',
                                    ' src=\'' . $is_index . '\'',
                                    ...setClass ([ setFileName ([ $is_set, 'photo' ]) ]),
                                    ...setStyle ([
                                        'align-self' => 'center',
                                        'height' => 'calc(100% - (' . $is_proper['theme']['margin'] . ' * 2 + ' . $is_proper['dot']['size'] . ') * 2)',
                                        'z-index' => 2,
                                        'border' => 'solid 1px ' . $is_proper['img']['border']['color'],
                                    ]),
                                '>',
                                '<div',
                                    ...setClass ([ setFileName ([ $is_set, 'filter' ]) ]),
                                    ...setStyle ([
                                        ...getStyle ('filter-blur', 25),
                                        'background-color' => 'rgba(0, 0, 0, .5)',
                                        'height' => '100%',
                                        'left' => 0,
                                        'position' => 'absolute',
                                        'top' => 0,
                                        'width' => '100%',
                                        'z-index' => 1,
                                    ]),
                                '>',
                                '</div>',
                            ] : [
                            ],
                        '</div>',
                    ]);
                }, $is_array, array_keys ($is_array)),
                ...array_map (function ($i, $k) use ($is_proper, $is_set) {
                    return implode ('', [
                        '<div',
                            ...setClass (setFileName ([ $is_set, 'arrow', $i ])),
                            ...setStyle ([
                                ...in_array ($is_proper['arrow']['position'], [ 'bottom' ]) ? [ 'bottom' => $is_proper['theme']['margin'] ] : [],
                                ...in_array ($is_proper['arrow']['position'], [ 'middle' ]) ? [ 'top' => 'calc((100% - ' . $is_proper['arrow']['size'] . ') / 2)' ] : [],
                                ...in_array ($is_proper['arrow']['position'], [ 'top' ]) ? [ 'top' => $is_proper['theme']['margin'] ] : [],
                                ...getStyle ('display-flex'),
                                $i => $is_proper['theme']['margin'],
                                'cursor' => 'pointer',
                                'height' => $is_proper['arrow']['size'],
                                'position' => 'absolute',
                                'width' => $is_proper['arrow']['size'],
                                'z-index' => 2,
                            ]),
                        '>',
                            '<div',
                                ...setClass (setFileName ([ $is_set, 'arrow', 'wrapper' ])),
                                ...setStyle ([
                                    ...getStyle ('display-flex'),
                                    ...getStyle ('circle-size', $is_proper['arrow']['size']),
                                    'background-color' => !$k ? setHexInvert ($is_proper['arrow']['bg']['color']) :  $is_proper['arrow']['bg']['color'],
                                    'position' => 'absolute',
                                    'z-index' => 2,
                                ]),
                            '>',
                                '<a',
                                    ...setClass (setFileName ([ $is_set, 'arrow' ])),
                                    ...setStyle ([
                                        'color' => !$k ? $is_proper['arrow']['bg']['color'] :  setHexInvert ($is_proper['arrow']['bg']['color']),
                                        'font-size' => $is_proper['arrow']['font-size'],
                                        'user-select' => 'none',
                                    ]),
                                '>',
                                    '<i', ...setClass ([ 'bi', 'fw-bolder', setFileName ([ 'bi', 'caret', $i, 'fill' ]) ]), '>', '</i>',
                                '</a>',
                            '</div>',
                            ...isTrue ($is_proper['arrow']['border']['active']) ? [
                                '<div',
                                    ...setClass (setFileName ([ $is_set, 'arrow', 'border' ])),
                                    ...setStyle ([
                                        ...getStyle ('circle-size', 'calc(' . $is_proper['arrow']['size'] . ' + ' . $is_proper['arrow']['border']['size'] . ' * 2)'),
                                        'background-color' => !$k ? setHexInvert ($is_proper['arrow']['bg']['color']) :  $is_proper['arrow']['bg']['color'],
                                        'opacity' => .25,
                                        'position' => 'absolute',
                                        'transition' => $is_proper['theme']['ease'],
                                        'z-index' => 1,
                                    ]),
                                '>',
                                '</div>',
                            ] : [
                            ],
                        '</div>',
                    ]);
                }, [ 'left', 'right' ], array_keys ([ 'left', 'right' ])),
                ...isTrue ($is_proper['dot']['active']) ? [
                    '<div',
                        ...setClass (setFileName ([ $is_set, 'dot', 'hidden' ])),
                        ...setStyle ([
                            ...getStyle ('display-flex'),
                            'bottom' => 0,
                            'height' => 'calc(' . $is_proper['theme']['margin'] . ' * 2 + ' . $is_proper['dot']['size'] . ')',
                            'overflow' => 'hidden',
                            'position' => 'absolute',
                            'width' => '100%',
                            'z-index' => 2,
                        ]),
                    '>',
                        '<div',
                            ...setClass (setFileName ([ $is_set, 'dot', 'container' ])),
                            ...setStyle ([
                                ...getStyle ('display-flex'),
                                'height' => $is_proper['dot']['size'],
                                'margin-left' => 'auto',
                                'position' => 'absolute',
                            ]),
                        '>',
                            ...array_map (function ($i, $k) use ($is_array, $is_proper, $is_set) {
                                return implode ('', [
                                    '<div',
                                        ...setClass (setFileName ([ $is_set, 'dot', 'wrapper' ])),
                                        ...setStyle ([
                                            ...getStyle ('display-flex'),
                                            ...$k < count ($is_array) - 1 ? [ 'margin-right' => $is_proper['dot']['gap'] ] : [],
                                            'cursor' => 'pointer',
                                        ]),
                                    '>',
                                        '<div',
                                            ...setClass (setFileName ([ $is_set, 'dot' ])),
                                            ...setStyle ([
                                                ...getStyle ('circle-size', $is_proper['dot']['size']),
                                                'background-color' => !$k ? setHexInvert ($is_proper['arrow']['bg']['color']) : $is_proper['arrow']['bg']['color'],
                                                'transition' => $is_proper['theme']['ease'],
                                                ...!isTrue ($is_proper['dot']['border']['active']) ? [ 'z-index' => 2 ] : [],
                                            ]),
                                        '>',
                                        '</div>',
                                        ...isTrue ($is_proper['dot']['border']['active']) ? [
                                            '<div',
                                                ...setClass (setFileName ([ $is_set, 'dot', 'border' ])),
                                                ...setStyle ([
                                                    ...getStyle ('circle-size', 'calc(' . $is_proper['dot']['size'] . ' + ' . $is_proper['dot']['border']['size'] . ' * 2)'),
                                                    'background-color' => !$k ? setHexInvert ($is_proper['arrow']['bg']['color']) : $is_proper['arrow']['bg']['color'],
                                                    'opacity' => .25,
                                                    'position' => 'absolute',
                                                    'transition' => $is_proper['theme']['ease'],
                                                    'z-index' => 1,
                                                ]),
                                            '>',
                                            '</div>',
                                        ] : [
                                        ],
                                    '</div>',
                                ]);
                            }, range (0, count ($is_array) - 1), array_keys (range (0, count ($is_array) - 1))),
                        '</div>',
                    '</div>',
                ] : [
                ],
            '</div>',
        ];
    };

?>