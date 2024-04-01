$(document).ready(function () {

    // **** This code is for sending throug pop-up to sendMailToContactProvider Function in adminProvider Page **** 

    $('.contact-btn').on("click", function () {
        let id = $(this).data('id');
        const url = `/admin/provider/${id}`;
        $('#ContactProviderForm').attr('action', url);
        $('.provider_id').val(id);
    })

    // ******************************************************************************** 



    // **** This code is for enabling field in adminProviderEdit Page **** 
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
        $(".listing-region").removeAttr("disabled");
    })


    $('#provider-profile-edit-btn').click(function () {
        $(".business-name").removeAttr("disabled");
        $(".business-web").removeAttr("disabled");
        $(".admin-notes").removeAttr("disabled");
    })




    // ********************************************************************************




    // ***************** Fetching regions from regions table ******************
    $.ajax({
        url: "/admin-new",
        type: "GET",
        success: function (data) {

            data.forEach(function (region) {
                $("#listing-region-admin-provider").append(
                    '<option value="' + region.id + '" class="regions-name" >' + region.region_name + "</option>"
                );
            });
        },
        error: function (error) {
            console.error(error);
        },

    });
    // ********************************************************************************



    // **** Filtering Data according to selected region from dropdown button in adminProvider Page ****

    // $('#listing-region-admin-provider').on('change', function () {
    //     var token = $('meta[name="csrf-token"]').attr('content')
    //     var selectedId = $(this).val();

    //     $.ajax({
    //         url: "/admin/providers/regionsFiltering",
    //         type: "POST",
    //         dataType: 'json',
    //         data: {
    //             regionId: selectedId,
    //             "_token": token
    //         },
    //         success: function (data) {
    //             $('#all-providers-data').html(data.html)
    //         },
    //         error: function (error) {
    //             console.error(error);
    //         }
    //     });
    // })

    // ********************************************************************************





    //***  This code is showing contact your provider pop-up ****
    $('.contact-btn').click(function () {
        $('.new-provider-pop-up').show();
        $('.overlay').show();
    })
    // **************************************************





    // *************************** This code is for show provider photo name ******************************

    $('.file-input-provider_photo').change(function (e) {
        const filename = e.target.files[0].name;
        $("#provider_photo").text(filename);
    });

    // *********************************************************




    // *************************** This code is for show provider signature photo name ******************************
    // 
    $('.file-input-provider_signature').change(function (e) {
        const filename = e.target.files[0].name;
        $("#provider_signature").text(filename);
    });

    // *********************************************************




    // *************************** This code is for show independent contractor agreement ******************************

    $('.independent-contractor-input').change(function (e) {
        const filename = e.target.files[0].name;
        $("#Contractor").text(filename);
    });

    // *********************************************************



    // *************************** This code is for show provider background photo name ******************************

    $('#background-input').change(function (e) {
        const filename = e.target.files[0].name;
        $("#Background").text(filename);
    });

    // *********************************************************




    // ************************ This code is for show provider HIPAA Compliance photo name *********************************

    $('#hipaa-input').change(function (e) {
        const filename = e.target.files[0].name;
        $("#HIPAA").text(filename);
    });

    // *********************************************************





    // *************************** This code is for show provider Non-disclosure Agreement photo name ******************************

    $('#non-disclosure-input').change(function (e) {
        const filename = e.target.files[0].name;
        $(".non-disclosure").text(filename);
    });

    // *********************************************************



    // *************************** This code is for show provider License  Agreement photo name ******************************

    $('#license-input').change(function (e) {
        const filename = e.target.files[0].name;
        $(".license").text(filename);
    });

    // *********************************************************




    // *** This code is for validation in contact provider pop-up

    $('#ContactProviderForm').validate({
        rules: {
            contact_msg: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
        },
        messages: {
            contact_msg: {
                required: "Please enter a message",
            },

        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('errorMsg');
            element.closest('.form-floating').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });

})


$(document).ready(function () {
    $.validator.addMethod("phoneUS", function (phone_number, element) {
        return this.optional(element) || phone_number.match(/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/);
    }, "Please enter a valid phone number.");

    $.validator.addMethod("city", function (value, element) {
        return value.match(/^[a-zA-Z ,_-]+?$/);
    }, "Please enter a valid city name.");

    $.validator.addMethod("zipcode", function (value, element) {
        return value.length == 6 && /\d/.test(value);
    }, "Please enter a valid zipcode.");

    $('#adminEditProviderForm').validate({
        rules: {
            user_name: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            password: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            email: {
                required: true,
                email: true,
            },
            phone_number: {
                required: true,
                phoneUS: true
            },
            medical_license: {
                required: true,
            },
            npi_number: {
                required: true,
            },
            alt_email: {
                required: true,
                email: true
            },
            address1: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            address2: {
                required: true,
            },
            city: {
                required: true,
                city: true
            },
            zip: {
                required: true,
                zipcode: true
            },
            alt_phone_number: {
                required: true,
                phoneUS: true
            },
            business_name: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            business_website: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            Admin_Notes: {
                required: true,
            },
        },
        message: {
            user_name: {
                required: "Please enter a valid username",
            },
            password: {
                required: "Please enter a valid password",
            },
            first_name: {
                required: "Please enter a valid first_name",
            },
            last_name: {
                required: "Please enter a valid last_name",
            },
            email: {
                required: "Please enter a valid email",
            },
            phone_number: {
                required: "Please enter a valid phone_number",
            },
            medical_license: {
                required: "Please enter a valid medical_license",
            },
            npi_number: {
                required: "Please enter a valid npi_number",
            },
            alt_email: {
                required: "Please enter a valid alt_email",
            },
            address1: {
                required: "Please enter a valid address1",
            },
            address2: {
                required: "Please enter a valid address2",
            },
            city: {
                required: "Please enter a valid city",
            },
            zip: {
                required: "Please enter a valid zipcode",
            },
            alt_phone_number: {
                required: "Please enter a valid alt_phone_number",
            },
            business_name: {
                required: "Please enter a valid business_name",
            },
            business_website: {
                required: "Please enter a valid business_website",
            },
            Admin_Notes: {
                required: "Please enter a valid Admin_Notes",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('errorMsg');
            element.closest('.form-floating').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    })
})



$(document).ready(function () {

    $('.contact-btn[id]').each(function (i, el) {
        var isChecked = $(el).closest('tr').find('.checkbox1').is(":checked");

        if (isChecked) {
            $(el).attr("disabled", "true")
        } else {
            $(el).removeAttr("disabled");
        }
    });



    $('#all-providers-data').on('change', '.checkbox1', function (e) {
        var token = $('meta[name="csrf-token"]').attr('content')
        var checkbox = $(this);

        var stopNotificationsCheckId = checkbox.attr('id').split('_')[1];
        var is_notifications = checkbox.prop('checked') ? 1 : 0; // Ternary operator to set is_notify

        $.ajax({
            url: "/admin/providers/stopNotification",
            type: 'POST',
            data: {
                stopNotificationsCheckId: stopNotificationsCheckId,
                is_notifications: is_notifications,
                "_token": token
            },
            success: function (response) {
                var contactBtn = $('#contact_btn_' + stopNotificationsCheckId)
                if (is_notifications == 1) {
                    contactBtn.prop('disabled', 'disabled')
                } else {
                    contactBtn.removeAttr('disabled')
                }
            },
            error: function (error) {
                console.error('Error updating stop notifications:', error);
            }
        });
    });
});



// $(document).ready(function () {
//     const fetch_data = (regions, page) => {
//         $.ajax({
//             url: "/admin/providers/regionsFiltering?page="+page,
//             type: "POST",
//             success: function (data) {
//                 $('#adminProviderData').html(data.html)
//             },
//             error: function (error) {
//                 console.error(error);
//             }
//         })
//     }

//     $('#listing-region-admin-provider').on('change',function (e) {
//         e.preventDefault();
//         var token = $('meta[name="csrf-token"]').attr('content')
//         var selectedId = $(this).val();
//         var page = $('#hidden_page').val();
//         fetch_data(page, regions);
//     });

//     $('#listing-region-admin-provider').on('click', '.pager a', function (event) {
//         event.preventDefault();
//         var token = $('meta[name="csrf-token"]').attr('content')
//         var selectedId = $(this).val();
//         var page = $(this).attr('href').split('page=')[1];
//         $('#hidden_page').val(page);
//         fetch_data(page, regions);
//     });
// })



// $('#listing-region-admin-provider').on('change', function () {
//     var token = $('meta[name="csrf-token"]').attr('content')
//     var selectedId = $(this).val();

//     $.ajax({
//         url: "/admin/providers/regionsFiltering",
//         type: "POST",
//         dataType: 'json',
//         data: {
//             regionId: selectedId,
//             "_token": token
//         },
//         success: function (data) {
//             $('#adminProviderData').html(data.html)
//         },
//         error: function (error) {
//             console.error(error);
//         }
//     });
// })


function fetchPaginatedResults(selectedId, page) {
    var token = $('meta[name="csrf-token"]').attr('content')
    $.ajax({
        url: '/admin/providers/regionsFiltering?page=' + page,
        type: 'POST',
        dataType: 'json',
        data: {
            selectedId: selectedId,
            "_token": token
        },
        success: function (data) {
            $('#adminProviderData').html(data.html) // Update results area
        }
    });
}

$(document).on('click', '.pagination .page-link', function (event) {
    event.preventDefault();
    var page = $(this).text();
    var selectedId = $("#listing-region-admin-provider").val();
    fetchPaginatedResults(selectedId, page);
});

$('#listing-region-admin-provider').on('change', function (event) {
    event.preventDefault();
    var selectedId = $(this).val();
    fetchPaginatedResults(selectedId, 1);
});



