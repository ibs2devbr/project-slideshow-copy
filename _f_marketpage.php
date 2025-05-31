<?php

    function setMarketPage (array $is_input = []): array {
        $is_sidebar = [
            ...getHeadlineTemplate ([
                'content' => configDescription,
                'heading' => 3,
            ]),
        ];
        $is_slideshow = [
            ...setSlideshow (),
        ];
        return [
            ...isArray ([ ...$is_slideshow, ...$is_sidebar ]) ? [
                '<div',
                    ...setClass ([
                        'd-flex',
                        'flex-column',
                        'flex-lg-row',
                        'h-100',
                        'w-100',
                    ]),
                '>',
                    ...isArray ($is_slideshow) ? [
                        '<div',
                            ...setClass ([
                                ...isArray ($is_sidebar) ? [ 'col-lg-9' ] : [],
                                'bg-danger',
                                'col-12',
                                'd-flex',
                                'height-absolute-320',
                                'height-relative-lg-100',
                            ]),
                        '>',
                            ...$is_slideshow,
                        '</div>',
                    ] : [
                    ],
                    ...isArray ($is_sidebar) ? [
                        '<div',
                            ...setClass ([
                                ...isArray ($is_slideshow) ? [ 'col-lg-3' ] : [],
                                'align-items-start',
                                'bg-black',
                                'col-12',
                                'd-flex',
                                'h-auto',
                                'p-3',
                            ]),
                        '>',
                            ...$is_sidebar,
                        '</div>',
                    ] : [
                    ],
                '</div>',
            ] : [
            ],
        ];
    };

?>