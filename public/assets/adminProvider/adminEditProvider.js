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



    // ***************** Fetching regions from regions table ******************
    $.ajax({
        url: "/admin-new",
        type: "GET",
        success: function (data) {

            data.forEach(function (region) {
                $("#listing-region-admin-provider").append(
                    '<option value="' + region.id + '" class="regions-name">' + region.region_name + "</option>"
                );
            });
        },
        error: function (error) {
            console.error(error);
        },

    });



    $('#listing-region-admin-provider').on('change', function () {
        var token = $('meta[name="csrf-token"]').attr('content')
        var selectedId = $(this).val();

        console.log(selectedId);

        $.ajax({
            url: "/regions",
            type: "POST",
            dataType: 'json',
            data: {
                regionId: selectedId,
                "_token": token
            },
            success: function (data) {
                $('#all-providers-data').html(data.html)
            },
            error: function (error) {
                console.error(error);
            }
        });
    })


    // this code is for new provider account page
    $('.contact-btn').click(function () {
        $('.new-provider-pop-up').show();
        $('.overlay').show();
    })


})