<?php

    foreach ([ 'config', 'define' ] as $is_archive):
        foreach (getFileArray ([ 'search' => $is_archive ]) as $is_index):
            $is_index = pathinfo ($is_index);
            define (setTargetName (explode ('-', $is_index['filename'])), setJson2Array ($is_index['basename']));
        endforeach;
    endforeach;

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

    function inArray (string $is_input = '', array|string $is_array = '', array|string $is_return = ''): array|string {
        return in_array ($is_input, setArray ($is_array)) ? $is_input : $is_return;
    };

    function setSlideShow (string $is_input = 'jpg', string $is_template = 'photo'): array {
        $is_set = 'slide';
        $is_array = [];
        foreach (array_diff (scandir ($is_input), [ '.', '..' ]) as $is_index)
            if (in_array (strtolower (pathinfo ($is_index)['extension']), defineExtensionPicture))
                $is_array[] = implode ('/', [ '.', $is_input, $is_index ]);
        $is_proper = [

            'dot-background' => '#292a2c',
            'dot-size' => '.5rem',

            'slide-margin-x' => '1rem',
            'slide-margin-y' => '2.5rem',
            'slide-template' => inArray ($is_template, [ 'photo', 'slide' ]),

            'transition-background' => '#000',
            'transition-color' => '#fff',
            'transition-margin' => '1rem',
            'transition-size' => '3rem',
            
        ];
        return [
            '<div',
                ...setClass ([ setFileName ([ $is_set, 'wrapper' ]) ]),
                ...setStyle ([
                    'height' => '100%',
                    'position' => 'relative',
                    'width' => '100%',
                ]),
            '>',
                ...array_map (function ($i, $k) use ($is_proper, $is_set) {
                    return implode ('', [
                        '<div',
                            ...setClass ([ setFileName ([ $is_set, 'content' ]) ]),
                            ...setStyle ([
                                'background-attachment' => 'scroll',
                                'background-image' => 'url(' . $i . ')',
                                'background-position' => 'center',
                                'background-repeat' => 'no-repeat',
                                'background-size' => 'cover',
                                'display' => !$k ? 'block' : 'none',
                                'height' => '100%',
                                'left' => 0,
                                'opacity' => !$k ? 1 : 0,
                                'position' => 'absolute',
                                'top' => 0,
                                'transition' => '0.35s ease-in-out',
                                'width' => '100%',
                                'z-index' => 1,
                            ]),
                        '>',
                            ...in_array ($is_proper['slide-template'], [ 'photo' ]) ? [
                                '<img',
                                    ' src=\'' . $i . '\'',
                                    ...setClass ([ setFileName ([ $is_set, 'photo' ]) ]),
                                    ...setStyle ([
                                        'height' => 'calc(100% - ' . $is_proper['slide-margin-y'] . ' * 2)',
                                        'left' => '50%',
                                        'position' => 'absolute',
                                        'top' => '50%',
                                        'transform' => 'translate(-50%, -50%)',
                                        'z-index' => 2,
                                    ]),
                                '>',
                                '<div',
                                    ...setClass ([ setFileName ([ $is_set, 'filter' ]) ]),
                                    ...setStyle ([
                                        '-webkit-backdrop-filter' => 'blur(5px)',
                                        'backdrop-filter' => 'blur(5px)',
                                        'background-color' => 'rgba(0, 0, 0, .75)',
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
                ...array_map (function ($i) use ($is_proper, $is_set) {
                    return implode ('', [
                        '<div',
                            ...setClass ([ setFileName ([ $is_set, $i ]) ]),
                            ...setStyle ([
                                ...$i === 'next' ? [ 'right' => $is_proper['transition-margin'] ] : [],
                                ...$i === 'prev' ? [ 'left' => $is_proper['transition-margin'] ] : [],
                                'cursor' => 'pointer',
                                'height' => $is_proper['transition-size'],
                                'position' => 'absolute',
                                'top' => 'calc((100% - ' . $is_proper['transition-size'] . ') / 2)',
                                'width' => $is_proper['transition-size'],
                                'z-index' => 2,
                            ]),
                        '>',
                            '<div',
                                ...setClass ([ setFileName ([ $is_set, 'border' ]), ]),
                                ...setStyle ([
                                    'background-color' => '#fff',
                                    'border-radius' => '50%',
                                    'height' => 'calc(' . $is_proper['transition-size'] . ' + .5rem)',
                                    'left' => '50%',
                                    'opacity' => .5,
                                    'position' => 'absolute',
                                    'top' => '50%',
                                    'transform' => 'translate(-50%, -50%)',
                                    'transition' => '0.35s ease-in-out',
                                    'width' => 'calc(' . $is_proper['transition-size'] . ' + .5rem)',
                                    'z-index' => 1,
                                ]),
                            '>',
                            '</div>',
                            '<div',
                                ...setClass ([ setFileName ([ $is_set, 'icon' ]) ]),
                                ...setStyle ([
                                    'align-items' => 'center',
                                    'background-color' => $is_proper['transition-background'],
                                    'border-radius' => '50%',
                                    'display' => 'flex',
                                    'height' => $is_proper['transition-size'],
                                    'justify-content' => 'center',
                                    'left' => '50%',
                                    'position' => 'absolute',
                                    'top' => '50%',
                                    'transform' => 'translate(-50%, -50%)',
                                    'width' => $is_proper['transition-size'],
                                    'z-index' => 2,
                                ]),
                            '>',
                                '<a',
                                    ...setStyle ([
                                        'color' => $is_proper['transition-color'],
                                        'font-size' => 'calc(' . $is_proper['transition-size'] . ' / 2)',
                                        'font-weight' => 'bold',
                                        'user-select' => 'none',
                                    ]),
                                '>',
                                    '<i',
                                        ...setClass ([
                                            'bi',
                                            'fw-bolder',
                                            ...$i === 'prev' ? [ 'bi-arrow-left' ] : [],
                                            ...$i === 'next' ? [ 'bi-arrow-right' ] : [],
                                        ]),
                                    '>',
                                    '</i>',
                                '</a>',
                            '</div>',
                        '</div>',
                    ]);
                }, [ 'prev', 'next' ]),
                '<div',
                    ...setStyle ([
                        'align-items' => 'center',
                        'bottom' => $is_proper['slide-margin-x'],
                        'display' => 'flex',
                        'justify-content' => 'space-between',
                        'left' => 'calc((100% - ' . $is_proper['dot-size'] . ' * ' . (count ($is_array) * 2 - 1) . ') / 2)',
                        'position' => 'absolute',
                        'width' => 'calc(' . $is_proper['dot-size'] . ' * ' . (count ($is_array) * 2 - 1) . ')',
                        'z-index' => 2,
                    ]),
                '>',
                    ...array_map (function ($i, $k) use ($is_proper, $is_set) {
                        return implode ('', [
                            '<span',
                                ...setClass ([ setFileName ([ $is_set, 'icon' ]) ]),
                                ...setStyle ([
                                    'background-color' => $is_proper['dot-background'],
                                    'border-radius' => '50%',
                                    'cursor' => 'pointer',
                                    'display' => 'block',
                                    'height' => $is_proper['dot-size'],
                                    'transition' => '0.35s ease-in-out',
                                    'width' => $is_proper['dot-size'],
                                ]),
                            '>',
                            '</span>',
                        ]);
                    }, range (0, count ($is_array) - 1), array_keys (range (0, count ($is_array) - 1))),
                '</div>',
            '</div>',
        ];
    };

?>