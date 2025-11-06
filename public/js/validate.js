jQuery.extend(jQuery.validator.messages, {
    required: "Campo obrigat&oacute;rio.",
    remote: "Corrija este campo.",
    email: "E-mail inv&aacute;lido.",
    url: "URL inv&aacute;lido.",
    date: "Data inv&aacute;lida.",
    dateISO: "Data (ISO) inv&aacute;lida.",
    number: "N&uacute;mero inv&aacute;lido.",
    digits: "Introduza apenas d&iacute;gitos.",
    creditcard: "Cart&atilde;o de cr&eacute;dito inv&aacute;lido.",
    equalTo: "Introduza o mesmo valor novamente.",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Número m&aacute;ximo de caracteres: {0}."),
    minlength: jQuery.validator.format("Número m&iacute;nimo de caracters: {0}."),
    rangelength: jQuery.validator.format("Introduza um valor entre {0} e {1} caracteres longos."),
    range: jQuery.validator.format("Introduza um valor entre {0} e {1}."),
    max: jQuery.validator.format("Introduza um valor menor ou igual a {0}."),
    min: jQuery.validator.format("Introduza um valor maior ou igual a {0}.")
});

jQuery.validator.addMethod("alpha_dash", function(value, element) {
    return this.optional(element) || value == value.match(/^[-a-zA-Z0-9_-|/]+$/);
}, "Introduza apenas letras, números, espaço e barra | ou /.");

jQuery.validator.addMethod("alpha", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z]+$/);
},"Introduza conter apenas letras.");

jQuery.validator.addMethod("alpha_num", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-z0-9A-Z#]+$/);
},"Introduza apenas letras e números.");

var files = {
    fileType: {
        types: ['pdf', 'jpg', 'png', 'jpeg','svg','tiff','raw','webp']
    },
    maxFileSize: {
        "unit": "MB",
        "size": 5
    },
    minFileSize: {
        "unit": "KB",
        "size": 1
    }
};
//ignore: ":disabled, :hidden, form:not(.steps) .ignore, .custom-file-control",
$.validator.setDefaults({
    
    rules:{
        'datasheet'     : files,
        'receipt'       : files,
        'fnif'          : files,
        'fbi'           : files,
        'fnbi'          : files,
        'frc'           : files,
        'attachmentf'   : files
    },
    highlight: function (input) {
        $(input).parents('.form-line').addClass('error focused');
    },
    unhighlight: function (input) {
        $(input).parents('.form-line').removeClass('error');
        $('label.error',$(input).parents('.form-line')).remove();
    },
    errorPlacement: function (error, element) {
        $(element).parents('.form-line').append(error);
    }
});


$(function(){

   $('form:not(.steps)').validate();

    $('form:not(.steps)').on('submit',function(e){
       
        if ($(this).valid()){
            $('.page-loader-wrapper').addClass('active');
            return true;
        }
        else
            return false
    });

    //$('form').submit();
});