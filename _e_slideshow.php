<?php

    function setSlideshow (string $is_input = 'jpg'): array {
        $is_set = 'slide';
        $is_proper = configSlideshow;
        $is_proper['arrow']['position'] = inArray ($is_proper['arrow']['position'], [ 'bottom', 'middle', 'top' ], 'middle');
        $is_proper['theme']['color'] = isValidHex ($is_proper['theme']['color']) ? $is_proper['theme']['color'] : '#fff';
        $is_proper['theme']['type'] = inArray ($is_proper['theme']['type'], [ 'photo', 'slide' ], 'photo');
        if (getNumber ($is_proper['arrow']['font-size']) > getNumber ($is_proper['arrow']['size']))
            $is_proper['arrow']['font-size'] = $is_proper['arrow']['size'];
        // $is_array = getINDEX (catalogContent, 'container');
        $is_array = getFileArray ([ 'dir' => $is_input ]);
        return [
            ...isArray ($is_array) ? [
                '<div',
                    ...setClass ([ setFileName ([ $is_set, 'wrapper' ]) ]),
                    ...setStyle ([ 'height' => '100%', 'position' => 'relative', 'width' => '100%' ]),
                '>',




                
                    ...array_map (function ($i, $k) use ($is_proper, $is_set) {
                        $is_arrow_border_size = getNumber ($is_proper['arrow']['border']['size']);
                        return implode ('', [
                            '<div',
                                ...setClass (setFileName ([ $is_set, 'arrow', $i ])),
                                ...setStyle ([
                                    ...in_array ($is_proper['arrow']['position'], [ 'bottom' ]) ? [ 'bottom' => $is_proper['theme']['margin'] ] : [],
                                    ...in_array ($is_proper['arrow']['position'], [ 'middle' ]) ? [ 'top' => 'calc((100% - ' . $is_proper['arrow']['size'] . ') / 2)' ] : [],
                                    ...in_array ($is_proper['arrow']['position'], [ 'top' ]) ? [ 'top' => $is_proper['theme']['margin'] ] : [],
                                    ...getStyle ('flex', 'center'),
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
                                        ...getStyle ('flex', 'center'),
                                        ...getStyle ('circle', $is_proper['arrow']['size']),
                                        ...isTrue ($is_arrow_border_size) ? [ 'z-index' => 2 ] : [],
                                        'background-color' => !$k ? setHexInvert ($is_proper['theme']['color']) :  $is_proper['theme']['color'],
                                        'position' => 'absolute',
                                    ]),
                                '>',
                                    '<a',
                                        ...setClass (setFileName ([ $is_set, 'arrow', 'button' ])),
                                        ...setStyle ([
                                            'color' => !$k ? $is_proper['theme']['color'] :  setHexInvert ($is_proper['theme']['color']),
                                            'font-size' => $is_proper['arrow']['font-size'],
                                            'user-select' => 'none',
                                        ]),
                                    '>',
                                        '<i', ...setClass ([ 'bi', 'fw-bolder', setFileName ([ 'bi', 'caret', $i, 'fill' ]) ]), '>', '</i>',
                                    '</a>',
                                '</div>',
                                ...isTrue ($is_arrow_border_size) ? [
                                    '<div',
                                        ...setClass (setFileName ([ $is_set, 'arrow', 'border' ])),
                                        ...setStyle ([
                                            ...getStyle ('circle', 'calc(' . $is_proper['arrow']['size'] . ' + ' . $is_proper['arrow']['border']['size'] . ' * 2)'),
                                            'background-color' => !$k ? setHexInvert ($is_proper['theme']['color']) :  $is_proper['theme']['color'],
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






                    ...isTrue (getNumber ($is_proper['dot']['size'])) ? [
                        '<div',
                            ...setClass (setFileName ([ $is_set, 'dot', 'hidden' ])),
                            ...setStyle ([
                                ...getStyle ('flex', 'center'),
                                'bottom' => 0,
                                'height' => 'calc(' . $is_proper['theme']['margin'] . ' * 2 + ' . $is_proper['dot']['size'] . ' + ' . $is_proper['dot']['border']['size'] . ' * 2)',
                                'overflow' => 'hidden',
                                'position' => 'absolute',
                                'width' => '100%',
                                'z-index' => 2,
                            ]),
                        '>',
                            '<div',
                                ...setClass (setFileName ([ $is_set, 'dot', 'container' ])),
                                ...setStyle ([
                                    ...getStyle ('flex', 'center'),
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
                                                ...getStyle ('flex', 'center'),
                                                'cursor' => 'pointer',
                                                'margin' => $k < count ($is_array) - 1 ? implode (' ', [ 0, $is_proper['dot']['gap'], 0, 0 ]) : 0,
                                            ]),
                                        '>',
                                            '<div',
                                                ...setClass (setFileName ([ $is_set, 'dot', 'button' ])),
                                                ...setStyle ([
                                                    ...getStyle ('circle', $is_proper['dot']['size']),
                                                    ...isTrue (getNumber ($is_proper['dot']['border']['size'])) ? [ 'z-index' => 2 ] : [],
                                                    'background-color' => !$k ? setHexInvert ($is_proper['theme']['color']) : $is_proper['theme']['color'],
                                                    'transition' => $is_proper['theme']['ease'],
                                                ]),
                                            '>',
                                            '</div>',
                                            ...isTrue (getNumber ($is_proper['dot']['border']['size'])) ? [
                                                '<div',
                                                    ...setClass (setFileName ([ $is_set, 'dot', 'border' ])),
                                                    ...setStyle ([
                                                        ...getStyle ('circle', 'calc(' . $is_proper['dot']['size'] . ' + ' . $is_proper['dot']['border']['size'] . ' * 2)'),
                                                        'background-color' => !$k ? setHexInvert ($is_proper['theme']['color']) : $is_proper['theme']['color'],
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
                    ...array_map (function ($i, $k) use ($is_proper, $is_set) {
                        $is_picture = $i;
                        // $is_picture = getPictureRandom (isKeyHasValidPath ($i, 'gallery'));
                        // $is_headline = getHeadlineTemplate ([
                        //     'content' => $i,
                        //     'heading' => 3,
                        //     'style' => [ 'color' => $is_proper['theme']['color'] ],
                        // ]);
                        $is_theme_type_photo = in_array ($is_proper['theme']['type'], [ 'photo' ]);
                        return implode ('', [
                            '<div',
                                ...setClass ([ setFileName ([ $is_set, 'content' ]) ]),
                                ...setStyle ([
                                    ...getStyle ('background-image', $is_picture),
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
                                ...isTrue ($is_theme_type_photo) ? [
                                    '<img',
                                        ' src=\'' . $is_picture . '\'',
                                        ...setClass ([ setFileName ([ $is_set, 'photo' ]) ]),
                                        ...setStyle ([
                                            ...isTrue (getNumber ($is_proper['dot']['size'])) ? [
                                                'height' => 'calc(100% - (' . $is_proper['theme']['margin'] . ' * 2 + ' . $is_proper['dot']['size'] . ' + ' . $is_proper['dot']['border']['size'] . ' * 2) * 2)'
                                            ] : [
                                                'height' => 'calc(100% - ' . $is_proper['theme']['margin'] . ' * 2)'
                                            ],
                                            'align-self' => 'center',
                                            'z-index' => 2,
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
                '</div>',
            ] : [
            ],
        ];
    };
?>