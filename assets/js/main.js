(()=>{var e={361:()=>{jQuery(document).ready((function(e){e(window).on("scroll",(function(){var o=window.pageYOffset||document.documentElement.scrollTop,t=e("#masthead").length?e("#masthead").outerHeight():e(window).outerHeight()/2;e("#colophon").length&&(e(".duam-feature-btn").offset().top,e("#colophon").offset().top),e(".duam-feature-btn").length&&(o>t?e(".duam-feature-btn").show(500).css("display","fixed"):e(".duam-feature-btn").hide().css("display","none"))}))}))},642:()=>{jQuery(document).ready((function(e){e(".duam-open-modal-btn").click((function(){e("#myModal").css("display","block"),console.log("Abre modal")})),e(".close").click((function(){e("#myModal").css("display","none")})),e(window).click((function(o){"myModal"===o.target.id&&e("#myModal").css("display","none")})),e(".nav-link").click((function(){var o=e(this).index();e(".nav-link").removeClass("active"),e(this).addClass("active"),e(".nav-content .content").removeClass("active"),e(".nav-content .content").eq(o).addClass("active")}))}))},107:()=>{console.log("I see you've opened the devtools\nIf you need to implement something like this you can reachme at\nmauriciofb@duck.com")}},o={};function t(n){var a=o[n];if(void 0!==a)return a.exports;var c=o[n]={exports:{}};return e[n](c,c.exports,t),c.exports}t.n=e=>{var o=e&&e.__esModule?()=>e.default:()=>e;return t.d(o,{a:o}),o},t.d=(e,o)=>{for(var n in o)t.o(o,n)&&!t.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:o[n]})},t.o=(e,o)=>Object.prototype.hasOwnProperty.call(e,o),(()=>{"use strict";t(361),t(642),t(107)})()})();