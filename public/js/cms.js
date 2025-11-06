var CMS = {
    onLoad: function () {
        $('[data-toggle="tooltip"]').tooltip();
    },
    callAjax: function (p) {
        $.ajax({
            url: p.url,
            type: p.type || 'POST',
            data: p.data,
            success: function (data) {
                console.log(data);
            },
            error: function (error) {
                console.log(error);
            }
        });

    },
    targert: {
        'submit': function (p) {
            p.obj.attr('action', p.url).submit();
        },
        'confirm': function (p) {
            var action = p.sel.attr('data-action') ? p.sel.attr('data-action') : 'submit';

            swal({
                title: p.sel.attr('alert-title') ? p.sel.attr('alert-title') : "Tem a certeza?",
                text: p.sel.attr('alert-text') ? p.sel.attr('alert-text') : 'Pretende Continuar',
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonColor: "#4caf50 ",
                confirmButtonText: "Continuar",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {

                    if (action == 'submit')
                        p.obj.attr('action', p.url).submit();
                    else if (p.sel.attr('data-url'))
                        window.location.href = p.sel.attr('data-url');
                }
            });
        }
    },

    onClick: function () {
        $('body').on('click', '[data-target="event"]', function (e) {
            e.preventDefault();

            CMS.targert[$(this).attr('data-rel')]({
                obj: $($(this).attr('data-obj')),
                url: $(this).attr('href'),
                sel: $(this).attr('data-select') || $(this)
            });

            return false;
        });
    },

    init: function () {
        CMS.onClick();
        CMS.onLoad();
        console.log('ok');
    }
};

$(function () {
    CMS.init();
});


$(document).ready(function () {
    var arrayVal = [];
    var arrayTitles = [];
    
    // Initialize IDs from hidden field
    var idDocs = ($('[name="iddocumentoartigo[]"]').val());
    if (idDocs != null && idDocs != '') {
        var res = idDocs.split(",");
        res = res.filter(Boolean);
        arrayVal = res;
    }
    
    // Initialize titles from hidden field (when editing)
    var existingTitles = $('#tituloDocumento').val();
    if (existingTitles != null && existingTitles != '') {
        var titlesArray = existingTitles.split(", ");
        titlesArray = titlesArray.filter(Boolean);
        arrayTitles = titlesArray;
    }
    
    // Update the visual list on page load
    updateDocumentsList();
    
    // When modal is shown, check the boxes for already attached documents
    $('#addDocs').on('shown.bs.modal', function () {
        // Small delay to ensure DataTable is fully rendered
        setTimeout(function() {
            // Uncheck all first
            $('.checkBoxTable').prop('checked', false);
            
            // Check boxes for documents that are already attached
            arrayVal.forEach(function(docId) {
                var checkbox = $('.checkBoxTable[value="' + docId + '"]');
                checkbox.prop('checked', true);
                
                // Also update the Material Design checkbox if needed
                var label = $('label[for="md_checkbox_' + docId + '"]');
                if (label.length) {
                    label.parent().find('input').prop('checked', true);
                }
            });
        }, 100);
    });

    $('.checkBoxTable').on('click', function (e) {
        var docId = e.target.value;
        var docTitle = $(e.target).closest('tr').find('td:eq(1)').text().trim(); // Get title from second column
        
        if (e.target.checked) {
            if (arrayVal.indexOf(docId) === -1) {
                arrayVal.push(docId);
                arrayTitles.push(docTitle);
                console.log('IDs:', arrayVal);
                console.log('Titles:', arrayTitles);
            }
        }
        else if (!e.target.checked) {
            for (var i = arrayVal.length - 1; i >= 0; i--) {
                if (arrayVal[i] === docId) {
                    arrayVal.splice(i, 1);
                    arrayTitles.splice(i, 1);
                    console.log('IDs:', arrayVal);
                    console.log('Titles:', arrayTitles);
                }
            }
        }
    });

    $("#buttonOK").on('click', function () {
        $('#addDocs').modal('hide');
        $('[name="iddocumentoartigo[]"]').val(arrayVal.join(','));
        $('#tituloDocumento').val(arrayTitles.join(', '));
        updateDocumentsList();
    });
    
    // Function to update the visual documents list
    function updateDocumentsList() {
        var listContainer = $('#documentosList');
        
        if (arrayVal.length === 0) {
            listContainer.html('<div class="alert alert-info">' +
                '<i class="material-icons" style="vertical-align: middle;">info</i> ' +
                'Nenhum documento anexado. Clique no botão acima para adicionar.' +
                '</div>');
        } else {
            var html = '<ul class="list-group">';
            for (var i = 0; i < arrayVal.length; i++) {
                html += '<li class="list-group-item" data-doc-id="' + arrayVal[i] + '">' +
                    '<i class="material-icons" style="vertical-align: middle; font-size: 18px;">description</i> ' +
                    '<span class="doc-title">' + arrayTitles[i] + '</span>' +
                    '<button type="button" class="btn btn-xs btn-danger pull-right remove-doc" data-doc-index="' + i + '">' +
                    '<i class="material-icons" style="font-size: 14px;">close</i>' +
                    '</button>' +
                    '</li>';
            }
            html += '</ul>';
            listContainer.html(html);
            
            // Bind remove button click events
            $('.remove-doc').on('click', function() {
                var index = $(this).data('doc-index');
                removeDocumentByIndex(index);
            });
        }
    }
    
    // Function to remove document by index
    function removeDocumentByIndex(index) {
        if (index >= 0 && index < arrayVal.length) {
            arrayVal.splice(index, 1);
            arrayTitles.splice(index, 1);
            $('[name="iddocumentoartigo[]"]').val(arrayVal.join(','));
            $('#tituloDocumento').val(arrayTitles.join(', '));
            updateDocumentsList();
            
            // Uncheck the checkbox in the table if modal is open
            var docId = arrayVal[index];
            $('.checkBoxTable[value="' + docId + '"]').prop('checked', false);
        }
    }
    
    // Make removeDocument function global for inline onclick handlers
    window.removeDocument = function(docId) {
        var index = arrayVal.indexOf(docId.toString());
        if (index > -1) {
            removeDocumentByIndex(index);
        }
    };

    $('.pastaArtHide').hide();
    $('.docHide').hide();
    $('.pastDocHide').hide();

    $('#ArtLink').on('click', function () {
        $('.ArtHide').show();
        $('.pastaArtHide').hide();
        $('.docHide').hide();
        $('.pastDocHide').hide();
    });

    $('#ArtPastaLink').on('click', function () {
        $('.ArtHide').hide();
        $('.pastaArtHide').show();
        $('.docHide').hide();
        $('.pastDocHide').hide();
    });

    $('#DocLink').on('click', function () {
        $('.ArtHide').hide();
        $('.pastaArtHide').hide();
        $('.docHide').show();
        $('.pastDocHide').hide();
    });

    $('#DocPastaLink').on('click', function () {
        $('.ArtHide').hide();
        $('.pastaArtHide').hide();
        $('.docHide').hide();
        $('.pastDocHide').show();
    });


    $('#idArtLink').on('change', function (e) {
        $('#idLink').val('/artigo/' + $(this).val());
    })

    $('#idArtPasta').on('change', function (e) {
        $('#idLink').val('/navart/' + $(this).val());
    })

    $('#idDocLink').on('change', function (e) {
        $('#idLink').val('/documento/' + $(this).val());
    })

    $('#idPastaDoc').on('change', function (e) {
        $('#idLink').val('/navdoc/' + $(this).val());
    })


    const validaDataPublicar = () => {
        const dataPublicao = new Date($('#idpublicar').val());
        const dataDespublicar = new Date($('#iddespublicar').val());

        $('.p-error-desp').remove();

        if (dataDespublicar < dataPublicao) {
            $('.error-despublicar').append('<p style="color:red;" class="p-error-desp">Data remoçāo menor que data de publicaçāo</p>');
        } else {
            $('.p-error-desp').remove();
        }
    }

    const validarDataCriacao = () => {
        const data_criacao = new Date($('#data_criacao').val());
        $('.p-error-desp1').remove();
        if (data_criacao > new Date()) {
            $('.error-data-criacao').append('<p style="color:red;" class="p-error-desp1">Data de criação tem que ser menor ou igual a hoje</p>');
        } else {
            $('.p-error-desp1').remove();
        }
    }

    $('#iddespublicar').focusout(validaDataPublicar);

    $('#data_criacao').focusout(validarDataCriacao);


});