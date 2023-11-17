jQuery(document).ready(function($) {
  $( window ).on( 'scroll', function() {
    var top = window.pageYOffset || document.documentElement.scrollTop;
    var scrollOffset = $( '#masthead' ).length ? $( '#masthead' ).outerHeight() : $( window ).outerHeight() / 2;
    let bottomOffset = $( '#colophon' ).length ? $( '.duam-feature-btn' ).offset().top - $( '#colophon' ).offset().top : 1;
    // TODO find a way to calculate distance of bottomOffset if btn is over footer
    
    if( $('.duam-feature-btn').length ) {
        if( top > scrollOffset ) { 
            $('.duam-feature-btn').show(500).css('display', 'fixed');
        } else {
            $('.duam-feature-btn').hide().css('display', 'none');
        }	
    }
  } );
});
