$('.btn btn-success, .imkush').on('click', function (e) {
    $("input[name='imkush']").val(1);
});

$('.btn btn-success, .holodbar').on('click', function (e) {
    $("input[name='holodbar']").val(1);
});

$('.btn btn-success, .export').on('click', function (e) {
    $("input[name='export']").val(1);
});

$('.btn btn-success, .mark_up_price').on('click', function (e) {
    $("input[name='mark_up_price']").val(1);
});

$('#markupgoods-percent').on('click', function (e) {
    $("#markupgoods-absolute").prop("checked", false);
});

$('#markupgoods-absolute').on('click', function (e) {
    $("#markupgoods-percent").prop("checked", false);
});

$( document ).ready(function() {
    if ( $('#markupgoods-percent').prop('checked') === false && $("#markupgoods-absolute").prop('checked') === false ) {
        $('#markupgoods-percent').prop('checked', true);
    }
});


