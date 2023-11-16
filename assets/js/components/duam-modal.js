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
});
