// import {
// } from './_.js';

// window.addEventListener ('DOMContentLoaded', Event => {
//     let index = 1;
//     setShowSlide (index);
//     function setPlusSlides (n) { setShowSlide (index += n); };
//     function setCurrentSlide (n) { setShowSlide (index = n); };
//     function setShowSlide (n) {
//         let i;
//         let slide = document.getElementsByClassName ('slide');
//         let dot = document.getElementsByClassName ('dot');
//         if (n > slide['length']) { index = 1 }
//         if (n < 1) { index = slide['length'] }
//         for (i = 0; i < slide['length']; i++) { slide[i]['classList'].remove ('active'); };
//         for (i = 0; i < dot['length']; i++) { dot[i]['className'] = dot[i]['className'].replace (' active', ''); };
//         if (slide['length'] > 0) { slide[index - 1]['classList'].add ('active'); };
//         if (dot['length'] > 0) { dot[index - 1]['className'] += ' active'; };
//     };
// });

// window.addEventListener ('resize', Event => {
// });

// window.addEventListener ('scroll', Event => {
// });