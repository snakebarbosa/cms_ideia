$(function(){
        var path = $('#path').val();

      $('#idMenu').on('change', function(e){

        $.ajax({
            method: "POST",
            url: path + '/Administrator/Item/getOptionsMenu',
            data: {
                  'idTipo' : $('#idMenu').val(),
                  '_token' : $("input[name='_token']").val()
                }
        })
        .done(function(data) {

            $('#idItems').empty();
             $.each(data, function(i, value) {
                        $('#idItems').append($('<option>').text(value).attr('value', i));
                    });

             $('#idItems').selectpicker('refresh')
        });

      });

       $('#idMenu').trigger("change");
    });