jQuery(document).ready(function($) {
  // Abrir modal al hacer clic en el botón
  $("#openModalBtn").click(function() {
    $("#myModal").css("display", "block");
  });

  // Cerrar modal al hacer clic en el botón de cierre (x)
  $(".close").click(function() {
    $("#myModal").css("display", "none");
  });

  // Cerrar modal al hacer clic fuera del contenido del modal
  $(window).click(function(event) {
    if (event.target.id === "myModal") {
      $("#myModal").css("display", "none");
    }
  });

  // Cambia de pestaña al hacer clic en un botón
  $(".nav-link").click(function() {
    var activeForm = $(this).index();
    
    $(".nav-link").removeClass("active");
    $(this).addClass("active");

    $(".nav-content .content").removeClass("active");
    $(".nav-content .content").eq(activeForm).addClass("active");
  })
});
