jQuery(document).ready(function($) {
  var featureCustoms = function(){
      var top = window.pageYOffset || document.documentElement.scrollTop;
      var scrollOffset = $( '#masthead' ).length ? $( '#masthead' ).outerHeight() : $( window ).outerHeight() / 2;
  
      if( $('.duam-feature-btn').length ) {
          if( top > scrollOffset ) { 
              $('.duam-feature-btn').show(500).css('display', 'fixed');
          } else {
              $('.duam-feature-btn').hide().css('display', 'none');
          }	
      }
    };
  featureCustoms();
  
  $( window ).on( 'scroll', featureCustoms );
    $( '#top' ).on( 'click', function() {
      $( 'html,body' ).stop().animate( { scrollTop: 0 } );
    } );
});
