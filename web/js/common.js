$('.btn btn-success, .imkush').on('click', function (e) {
    $("input[name='imkush']").val(1);
});

$('.btn btn-success, .holodbar').on('click', function (e) {
    $("input[name='holodbar']").val(1);
});

$('#markupgoods-percent').on('click', function (e) {
    $("#markupgoods-absolute").prop("checked", false);
});

$('#markupgoods-absolute').on('click', function (e) {
    $("#markupgoods-percent").prop("checked", false);
});


