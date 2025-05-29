<?php

    function getHeadlineTemplate (array $is_input = []): array {
        $is_proper = getHeadlineProper ($is_input);
        $is_subtitle = isKeyTrue ($is_proper['content'], 'subtitle');
        $is_description = isKeyTrue ($is_proper['content'], 'description');
        $is_content = [
            ...isTrue ($is_description) ? [ '<div', ...setClass (getClass ('wrapColumn')), '>' ] : [],
                ...getHeadlineTitle ($is_proper, 'title'),
                ...getHeadlineTitle ($is_proper, 'subtitle'),
            ...isTrue ($is_description) ? [ '</div>' ] : [],
            ...getHeadlineDescription ($is_proper),
        ];
        return getHeadlineHeader ($is_content);
    };

    function getHeadlineProper (array $is_input = [], string $is_excluded = ''): array {
        $is_attrib = [];
        foreach ([ 'align', 'bullet', 'content', 'heading' ] as $is_index):
            if (in_array ($is_index, setArray ($is_excluded))): else:
                $is_attrib = array_merge ($is_attrib, setProper ($is_input, $is_index));
            endif;
        endforeach;
        return $is_attrib;
    };

    function getHeadlineTitle (array $is_input = [], string $is_key = 'subtitle'): array {
        return isKeyTrue ($is_input, 'content') ? [
            ...isKeyTrue ($is_input['content'], $is_key) ? [
                ...array_map (function ($is_index) use ($is_input, $is_key) {
                    $is_head = 'text'; $is_wrap = 'p';
                    if (in_array ($is_key, [ 'title' ]))
                        if (isKeyTrue ($is_input, 'heading'))
                            if (in_array ($is_input['heading'], range (1, 6)))
                                $is_head = $is_wrap = 'h' . $is_input['heading'];
                    return implode ('', [
                        '<',
                            $is_wrap,
                            ...setClass ([
                                $is_key,
                                getClass ($is_head),
                                ...isKeyTrue ($is_input, 'align') ? [
                                    ...in_array ($is_input['align'], [ 'end', 'start' ]) ? [ implode ('-', [ 'text', 'lg', $is_input['align'] ]) ] : [],
                                ] : [
                                ],
                            ]),
                        '>',
                            setCamelcase (setFullStop ($is_index)),
                        '</', $is_wrap, '>',
                    ]);
                }, setArray ($is_input['content'][$is_key])),
            ] : [
            ],
        ] : [
        ];
    };

    function setFullStop (string $is_input = ''): string {
        return in_array ($is_input[strlen ($is_input) - 1], defineDot) ? $is_input : $is_input . '.';        
    };

    function getHeadlineDescription (array $is_input = []): array {
        return isKeyTrue ($is_input, 'content') ? [
            ...isKeyTrue ($is_input['content'], 'description') ? [
                ...getOrderedList ([
                    ...getHeadlineProper ($is_input, 'content'),
                    'content' => $is_input['content']['description'],
                ]),
            ] : [
            ],
        ] : [
        ];
    };

    function getOrderedList ($is_input = [], $is_number = 0) {
        $is_proper = getHeadlineProper ($is_input);
        $is_proper['align'] = in_array ($is_proper['align'], [ 'start', 'end' ]) ? $is_proper['align'] : [];
        return isArray ($is_proper['content']) ? [
            '<ul',
                ...setClass ([
                    ...in_array ($is_proper['bullet'], [ 'yes' ]) ? sizeof ($is_proper['content']) > 1 ? [ 'border', 'border-0', 'list-group', 'list-group-numbered', 'list-group-flush' ] : [] : [],
                    ...isTrue ($is_proper['align']) ? [
                        ...$is_number > 0 ? [ implode ('-', [ 'p' . $is_proper['align'][0], 'lg-3' ]) ] : [],
                        setFileName ([ 'justify', 'content', 'lg', $is_proper['align'] ]),
                    ] : [
                    ],
                    'bg-transparent',
                    'd-flex',
                    'flex-column',
                    'justify-content-center',
                    'list-unstyled',
                    'm-0',
                    'p-0',
                ]),
            '>',
                ...array_map (function ($is_index, $is_key) use ($is_proper, $is_number) {
                    $is_number++;
                    return implode ('', isString ($is_index) ? [
                        '<li',
                            ...setClass ([
                                ...in_array ($is_proper['bullet'], [ 'yes' ]) ? [ 'list-group-item', 'px-0', 'py-2' ] : [ 'p-0' ],
                                ...isTrue ($is_proper['align']) ? [ setFileName ([ 'text', 'lg', $is_proper['align'] ]) ] : [],
                                'bg-transparent',
                                'd-inline',
                                'lh-1',
                                'm-0',
                                'text-center',
                            ]),
                        '>',
                            ...in_array ($is_proper['bullet'], [ 'yes' ]) ? sizeof ($is_proper['content']) < 2 ? [ 'PARÁGRAFO ÚNICO: ' ] : [] : [],
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
        $is_lighted = array_unique (defineEmphasis);
        return isArray (setArray ($is_input)) ? preg_replace_callback (implode ('', [
            '/\b(', implode ('|', [ ...array_map ('strtolower', $is_lighted), ...array_map ('strtoupper', $is_lighted), ...array_map ('ucfirst', $is_lighted), ...array_map ('ucwords', $is_lighted) ]), ')\b/i'
        ]), function ($is_index) { return implode ('', [ '<em>', '<b>', ucwords ($is_index[0]), '</b>', '</em>' ]); }, implode ('', setArray ($is_input))) : '';
    };

    function getHeadlineHeader (array $is_input = []): array {
        return [
            ...isArray ($is_input) ? [
                '<header', ...setClass ([ getClass ('gap1'), getClass ('wrapColumn') ]), ...setStyle ([ 'width' => 'fit-content' ]), '>',
                    ...$is_input,
                '</header>',
            ] : [
            ],
        ];
    };

?>