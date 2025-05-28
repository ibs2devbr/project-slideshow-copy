<?php

    include_once ('_function.php');

    define ('defineMainContent', [
    ]);

    define ('defineFooterContent', [
    ]);

    define ('defineBodyContent', [
        ...setSlideShow (),
        '<script>
            let index = 1;
            setShowSlide (index);
            function setPlusSlides (n) { setShowSlide (index += n); };
            function setCurrentSlide (n) { setShowSlide (index = n); };
            function setShowSlide (n) {
                let i;
                const slide = document.getElementsByClassName (\'slide\');
                const dot = document.getElementsByClassName (\'dot\');
                if (n > slide[\'length\']) { index = 1 }
                if (n < 1) { index = slide[\'length\'] }
                for (i = 0; i < slide[\'length\']; i++) { slide[i][\'classList\'].remove (\'active\'); };
                for (i = 0; i < dot[\'length\']; i++) { dot[i][\'className\'] = dot[i][\'className\'].replace (\' active\', \'\'); };
                if (slide[\'length\'] > 0) { slide[index - 1][\'classList\'].add (\'active\'); };
                if (dot[\'length\'] > 0) { dot[index - 1][\'className\'] += \' active\'; };
            };
        </script>',
        // ...setSELETOR (defineMainContent, [ 'wrap' => 'main' ]),
        // ...setSELETOR (defineFooterContent, [ 'wrap' => 'footer' ]),
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