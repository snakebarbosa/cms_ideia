function ajaxImg(idCat) {
   var path = $('#path').val();
   var path2 = $('#path2').val();
   var deleteImg = $('#deleteImg').val();

   $.ajax({
      type: "GET",
      data: {
         categoria: idCat
      },
      url: path + '/Administrator/Midia/loadAjax',
      datatype: "json",
      success: function (imagens) {

         var img = '';
         for (var i = 0; i < imagens.length; i++) {

            img += '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">' + '<div class="showImage">';
            if (deleteImg) {
               //  console.log(imagens[i].url)
               img += '<a title="' + imagens[i].titulo + '" href="' + path2 + "/" + imagens[i].url + '">' +
                  '<img class="thumbnail img-responsive" style="height:100px!important;width:200px;" src="' + path2 + "/" + imagens[i].url + '">' +
                  '</a>';
            } else {

               img += '<img class="thumbnail img-responsive imagegal" style="height:100px!important;width:200px;" data-id="' + imagens[i].id + '" data-title="' + imagens[i].url + '" src="' + path2 + "/" + imagens[i].url + '">';

            }

            if (deleteImg) {
               img += '<div class="botaoDelImagem">' +
                  '<button id="delete" type="button" data-target="event" data-obj="#formDelImg" data-rel="confirm" alert-text="Deseja eliminar esta imagem?" data-action="submit" href="' + path + '/Administrator/Imagem/' + imagens[i].id + '" class="btn btn-danger  waves-effect" >' +
                  '<i class="material-icons">clear</i>' +
                  '</button>' +
                  '</div>';
            }
            img += '</div></div>';

         }

         $('#aniimated-thumbnials').html(img);

         initGaleria();


      }
   });


}




function initGaleria() {

   $("#aniimated-thumbnials .showImage img.imagegal").on('click', function () {
      $("#aniimated-thumbnials .showImage img.imagegal").removeClass('hover');
      $(this).toggleClass("hover");


   });

   if ($('#aniimated-thumbnials').data('lightGallery')) {
      $('#aniimated-thumbnials').data('lightGallery').destroy(true);

      $('#aniimated-thumbnials').lightGallery({
         thumbnail: true,
         // loop:true,
         selector: 'a'
      });
   }
}




$(document).ready(function () {


   $("input:radio[name=iDcategoria]").on("change", function () {
      var categoriaI = $(this).attr("value");
      $("#categoria").attr("value", categoriaI);
      ajaxImg(categoriaI);
   });
   // console.log($('input.raizID').attr('data-obj'));
   ajaxImg($('input.raizID').attr('data-obj'));


   $("#cancelModalGal").on('click', function () {
      $("#aniimated-thumbnials .showImage img.imagegal").removeClass('hover');
   });

   $("#okModalGal").on('click', function () {
      var element = $("#aniimated-thumbnials .showImage img.hover");

      if (element.attr('data-id')) {
         $('#idimagem').val(element.attr('data-id'));
         $('#tituloIMG').val(element.attr('data-title'));

         $('#largeModal').modal('hide');

      } else {
         alert("Nenhuma imagem foi selecionada.");
      }


      // console.log($("#aniimated-thumbnials .showImage img.hover").attr('data-id')); 
   });





});
