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
    })
    // ********************************************************************************
    
    
    
    
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
        // ********************************************************************************
        
        
        
    // **** Filtering Data according to selected region from dropdown button in adminProvider Page ****
    
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

})