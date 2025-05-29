<?php

    function isBoxShadow (string $is_input = ''): bool {
        $is_pattern = '/^';
            for ($i = 0; $i < 4; $i++):
                $is_pattern .= '(0|\d+px)';
                $is_pattern .= '\s';
            endfor;
            $is_pattern .= 'rgba\(';
                for ($i = 0; $i < 3; $i++):
                    $is_pattern .= '\d+';
                    $is_pattern .= ',';
                    $is_pattern .= '\s';
                endfor;
                $is_pattern .= '(';
                    $is_pattern .= '0|0?\.\d+|1(\.0*)?';
                $is_pattern .= ')';
            $is_pattern .= '\)';
        $is_pattern .= '$/';
        return preg_match ($is_pattern, $is_input);
    };

    function isTextShadow (string $is_input = ''): bool {
        $is_pattern = '/^';
            for ($i = 0; $i < 3; $i++):
                $is_pattern .= '(0|\d+px)';
                $is_pattern .= '\s';
            endfor;
            $is_pattern .= 'rgba\(';
                for ($i = 0; $i < 3; $i++):
                    $is_pattern .= '\d+';
                    $is_pattern .= ',';
                    $is_pattern .= '\s';
                endfor;
                $is_pattern .= '(';
                    $is_pattern .= '0|0?\.\d+|1(\.0*)?';
                $is_pattern .= ')';
            $is_pattern .= '\)';
        $is_pattern .= '$/';
        return preg_match ($is_pattern, $is_input);
    };

    function getStyle (string $is_key = '', float|string $is_input = ''): array {
        $is_result = [
            ...isTextShadow ($is_input) ? [
                '-webkit-text-shadow' => $is_input,
                '-moz-text-shadow' =>$is_input,
                'text-shadow' => $is_input,
            ] : [
            ],
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
            'backdrop-filter-blur' => [
                '-webkit-backdrop-filter' => 'blur(' . (in_array ($is_input, setDecimalRange (100)) ? $is_input : 25) . 'px)',
                'backdrop-filter' => 'blur(' . (in_array ($is_input, setDecimalRange (100)) ? $is_input : 25) . 'px)',
            ],
            'box-shadow' => [
                ...isBoxShadow ($is_input) ? [
                    '-webkit-box-shadow' => $is_input,
                    '-moz-box-shadow' =>$is_input,
                    'box-shadow' => $is_input,
                ] : [
                ],
            ],
        ];
        if (isKeyTrue ($is_result, $is_key)) return $is_result[$is_key];
        return [];
    };

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

?>