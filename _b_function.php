<?php

    defineVariableCreator ([ 'catalog', 'config', 'define' ]);

    function getDefineKeyword (): array {
        $is_result = [];
        $is_i_array = configKeyword[0];
        $is_j_array = isArray (configKeyword[1]) ? configKeyword[1] : defineGenero;
        $is_k_array = configKeyword[2];
        for ($i = 0; $i < sizeof ($is_i_array); $i++)
            for ($j = 0; $j < sizeof ($is_j_array); $j++)
                for ($k = 0; $k < sizeof ($is_k_array); $k++)
                    array_push ($is_result, setCamelcase (implode (' ', [
                        $is_i_array[$i],
                        $is_j_array[$j],
                        $is_k_array[$k],
                    ])));
        shuffle ($is_result);
        return $is_result;
    };

    define ('configKeywordArray', getDefineKeyword ());

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

    function setCamelcase (array|string $is_input = ''): string {
        $is_input = implode (' ', setArray ($is_input));
        $is_input = preg_replace ('/\s+/', ' ', $is_input);
        $is_input = explode (' ', trim ($is_input));
        return implode (' ', array_map (function ($i) { return in_array ($i, defineLowercase) ? strtolower ($i) : ucfirst ($i); }, $is_input));
    };

    function setSELETOR (array $is_input = [], array $is_proper = []): array {
        $is_proper = setProper ($is_proper, [ 'id', 'style', 'wrap' ]);
        $is_wrap = in_array ($is_proper['wrap'], defineSeletor) ? $is_proper['wrap'] : 'div';
        return [
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
        ];
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
        if (!array_is_list ($is_input)):
            $is_input = setSortKey ($is_input);
            return setAttrib (implode (' ', array_map (function ($is_index) use ($is_input) {
                return implode ('', [ $is_index, ': ', $is_input[$is_index], ';' ]);
            }, array_keys ($is_input))), 'style');
        endif;
        return [];
    };

    function setHeadTitle (): array {
        $is_array = array_filter (array_map (function ($is_index) {
            if (isKeyTrue (configDescription, $is_index))
                    return setCamelcase (configDescription[$is_index]);
        }, [ 'title', 'subtitle', 'description' ]));
        return [ '<title>', ...isArray ($is_array) ? [ implode (' | ', $is_array) ] : [ '|' ], '</title>' ];
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

?>