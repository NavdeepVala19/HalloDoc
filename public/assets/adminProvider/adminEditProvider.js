$(document).ready(function () {

    $("#provider-credentials-edit-btn").click(function () {
        $(".provider-username-field").removeAttr("disabled");
        $("#provider-status").removeAttr("disabled");
        $("#provider-role").removeAttr("disabled");
    });

    $('#provider-reset-password-btn').click(function () {
        $('.provider-password-field').removeAttr("disabled");
    })

    $('#provider-info-btn').click(function () {
        $(".provider-firstname").removeAttr("disabled");
        $(".provider-lastname").removeAttr("disabled");
        $(".provider-email").removeAttr("disabled");
        $("#telephone").removeAttr("disabled");
        $(".provider-license").removeAttr("disabled");
        $(".provider-npi").removeAttr("disabled");
        $(".provider-alt-email").removeAttr("disabled");
    })

    $('#provider-bill-edit-btn').click(function () {
        $(".provider-bill-add1").removeAttr("disabled");
        $(".provider-bill-add2").removeAttr("disabled");
        $(".provider-bill-city").removeAttr("disabled");
        $(".provider-bill-zip").removeAttr("disabled");
        $(".alt-phone-provider").removeAttr("disabled");
    })

})