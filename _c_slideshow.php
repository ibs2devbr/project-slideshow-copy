<?php

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