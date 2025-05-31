<?php

    foreach (scandir ('./') as $is_index)
        if (preg_match ('/^_[a-z]{1}_[a-z-]*\.php$/', $is_index))
            require_once ($is_index);

    define ('defineMainContent', [
    ]);

    define ('defineFooterContent', [
    ]);

    define ('defineBodyContent', [
        ...setMarketPage (),
    ]);

    define ('defineFloatContent', [
    ]);

    define ('defineHeadContainer', setSELETOR ([
        ...setMetaWrapper (setAttrib ('utf-8', 'charset')),
        ...setMetaViewport (),
        ...setMetaDescription (),
        ...setMetaKeyword (),
        ...setHeadTitle (),
        ...setStyleArray ()
    ], [ 'wrap' => 'head' ]));

    define ('defineBodyContainer', setSELETOR ([
        ...defineBodyContent,
        ...defineFloatContent,
        ...setScriptArray ()
    ], [ 'wrap' => 'body' ]));

    define ('defineHTMLContent', [
        '<!doctype html>',
        '<html',
            ...setClass (getClass ('html')),
            ' data-bs-theme=\'', defineTheme['color'], '\'',
            ' lang=\'en\'',
        '>',
            ...defineHeadContainer,
            ...defineBodyContainer,
        '</html>',
    ]);

    ob_start ();
    echo implode ('', defineHTMLContent);
    $is_html_content = ob_get_contents ();
    ob_end_clean ();
    file_put_contents (defineServer['html-file'], $is_html_content);
    echo $is_html_content;

?>