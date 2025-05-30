<?php

    require_once ('function.php');

    $is_array = [];
    foreach (scandir ('./') as $is_index)
        if (preg_match ('/^_[a-z_-]*\.php$/', $is_index))
            $is_array[] = $is_index;

    $is_number = 0;
    if (file_exists (defineServer['html-file'])):
        $is_html_datetime = getFileDateTime (defineServer['html-file']);
        foreach ([ ...getPathArray ([ 'dir' => 'json' ]), ...$is_array ] as $is_index):
            if (getFileDateTime ($is_index) > $is_html_datetime)
                $is_number++;
        endforeach;
    else:
        $is_number++;
    endif;

    if (isTrue ($is_number)): echo setMetaRefresh (defineServer['php-file']); else:
        echo setMetaRefresh (defineServer['html-file']);
    endif;

?>