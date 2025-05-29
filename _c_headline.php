<?php

    function getHeadlineTemplate (array $is_input = []): array {
        $is_proper = getHeadlineProper ($is_input);
        $is_subtitle = isKeyTrue ($is_proper['content'], 'subtitle');
        $is_description = isKeyTrue ($is_proper['content'], 'description');
        return getHeadlineHeader ([
            ...isTrue ($is_description) ? [ '<div', ...setClass (getClass ('wrapColumn')), '>' ] : [],
                ...getHeadlineTitle ($is_proper, 'title'),
                ...getHeadlineTitle ($is_proper, 'subtitle'),
            ...isTrue ($is_description) ? [ '</div>' ] : [],
            ...getHeadlineDescription ($is_proper),
        ]);
    };

    function getHeadlineProper (array $is_input = [], string $is_excluded = ''): array {
        $is_attrib = [];
        foreach ([ 'content', 'heading' ] as $is_index):
            if (in_array ($is_index, setArray ($is_excluded))): else:
                $is_attrib = array_merge ($is_attrib, setProper ($is_input, $is_index));
            endif;
        endforeach;
        return $is_attrib;
    };

    function getHeadlineTitle (array $is_input = [], string $is_key = 'subtitle'): array {
        if (isKeyTrue ($is_input, 'content')):
            if (isKeyTrue ($is_input['content'], $is_key)):
                return array_map (function ($is_index) use ($is_input, $is_key) {
                    $is_head = 'text'; $is_wrap = 'p';
                    if (in_array ($is_key, [ 'title' ]))
                        if (isKeyTrue ($is_input, 'heading'))
                            if (in_array ($is_input['heading'], range (1, 6)))
                                $is_head = $is_wrap = 'h' . $is_input['heading'];
                    return implode ('', [
                        '<',
                            $is_wrap,
                            ...setClass ([ $is_key, 'd-inline', 'lh-1', 'm-0', 'p-0', 'text-start' ]),
                        '>',
                            setCamelcase (setFullStop ($is_index)),
                        '</', $is_wrap, '>',
                    ]);
                }, setArray ($is_input['content'][$is_key]));
            endif;
        endif;
        return [];
    };

    function setFullStop (string $is_input = ''): string {
        return in_array ($is_input[strlen ($is_input) - 1], defineDot) ? $is_input : $is_input . '.';        
    };

    function getHeadlineDescription (array $is_input = []): array {
        if (isKeyTrue ($is_input, 'content')):
            if (isKeyTrue ($is_input['content'], 'description')):
                return getOrderedList ([
                    ...getHeadlineProper ($is_input, 'content'),
                    'content' => $is_input['content']['description'],
                ]);
            endif;
        endif;
        return [];
    };

    function getOrderedList ($is_input = [], $is_number = 0) {
        $is_proper = getHeadlineProper ($is_input);
        return isArray ($is_proper['content']) ? [
            '<ul', ...setClass ([ 'bg-transparent', 'd-flex', 'flex-column', 'list-unstyled', 'm-0', 'p-0' ]), '>',
                ...array_map (function ($is_index, $is_key) use ($is_proper, $is_number) {
                    $is_number++;
                    return implode ('', isString ($is_index) ? [
                        '<li', ...setClass ([ 'bg-transparent', 'd-inline', 'lh-1', 'm-0', 'p-0', 'text-start' ]), '>',
                            isURL ($is_index) ? setHyperlink ($is_index) : setEmphasis ($is_index),
                            ...getOrderedList ([
                                ...getHeadlineProper ($is_proper, 'content'),
                                'content' => $is_proper['content'][$is_key + 1 < sizeof ($is_proper['content']) ? $is_key + 1 : $is_key],
                            ], $is_number),
                        '</li>',
                    ] : [
                    ]);
                }, $is_proper['content'], array_keys ($is_proper['content'])),
            '</ul>',
        ] : [
        ];
    };

    function setEmphasis (array|string $is_input = ''): string {        
        $is_emphasis = array_unique (defineEmphasis);
        if (isArray (setArray ($is_input))):
            return preg_replace_callback (implode ('', [
                '/\b(', implode ('|', [ ...array_map ('strtolower', $is_emphasis), ...array_map ('strtoupper', $is_emphasis), ...array_map ('ucfirst', $is_emphasis), ...array_map ('ucwords', $is_emphasis) ]), ')\b/i'
            ]), function ($is_index) { return implode ('', [ '<em>', '<b>', ucwords ($is_index[0]), '</b>', '</em>' ]); }, implode ('', setArray ($is_input)));
        endif;
        return '';
    };

    function getHeadlineHeader (array $is_input = []): array {
        if (isArray ($is_input)):
            return [
                '<header',
                    ...setClass ([ getClass ('gap1'), getClass ('wrapColumn') ]),
                    ...setStyle ([ 'width' => 'fit-content' ]),
                '>',
                    ...$is_input,
                '</header>',
            ];
        endif;
        return [];
    };

?>