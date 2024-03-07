
// this code is for create a new request in patient dashboard page
$(document).ready(function () {
    $('.create-btn').click(function () {
        $('.new-request').show();
    })


    // *********************************************************
    // this code is for create new request pop-up 

    $('.btn-someone').click(function () {
        $(this).toggleClass('btn-active');
        $('.btn-me').removeClass('btn-active');
    })
    $('.btn-me').click(function () {
        $(this).toggleClass('btn-active');
        $('.btn-someone').removeClass('btn-active');
    })

    $('.continue-btn').click(function () {
        if ($('.btn-me').hasClass('btn-active')) {
            $(window).attr('location', '/createPatientRequests');
        } else {
            $(window).attr('location', '/createSomeoneRequests');

        }
    })

    // this code is for new provider account page
    $('.contact-btn').click(function () {
        $('.new-provider-pop-up').show();
        $('.overlay').show();
    })

    $('.request-support-btn').click(function () {
        $('.request-support').show();
    })


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


    $('.contact-btn').on("click", function () {
        let id = $(this).data('id');

        const url = `/admin/provider/${id}`;
        $('#ContactProviderForm').attr('action', url);

        $('.provider_id').val(id);
    })

})



// *********************************************************

$(".master-checkbox").on("click", function () {
    if ($(this).is(":checked", true)) {
        $(".child-checkbox").prop("checked", true);
    } else {
        $(".child-checkbox").prop("checked", false);
    }
});

// *********************************************************
$(".master-checkbox").on("click", function () {
    if ($(this).is(":checked", true)) {
        $(".child-checkbox").prop("checked", true);
    } else {
        $(".child-checkbox").prop("checked", false);
    }
});

// *********************************************************



// *********************************************************
// this code is for show file name
$('.file-input').change(function (e) {
    const filename = e.target.files[0].name;
    $("#demo").text(filename);
});

// *********************************************************






// *********************************************************
// this is use for showing agreement cancel pop-up

$(document).ready(function () {
    $('.cancel').click(function () {
        $('.cancel-pop-up').show();
    })

})

// *********************************************************





// **********************************************************************
// this is for password showing and hiding password in input field

const passwordField = document.getElementById('exampleInputPassword1');
const togglePassword = document.querySelector('.person-eye');

const confirmpasswordField = document.getElementById('exampleInputPassword2');
const togglePasswordTwo = document.querySelector('.person-eye-two');


togglePassword.addEventListener('click', () => {
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        togglePassword.classList.remove('bi-eye-fill');
        togglePassword.classList.add('bi-eye-slash-fill');
    } else {
        passwordField.type = 'password';
        togglePassword.classList.remove('bi-eye-slash-fill');
        togglePassword.classList.add('bi-eye-fill');
    }
});



togglePasswordTwo.addEventListener('click', () => {
    if (confirmpasswordField.type === 'password') {
        confirmpasswordField.type = 'text';
        togglePasswordTwo.classList.remove('bi-eye-fill');
        togglePasswordTwo.classList.add('bi-eye-slash-fill');
    } else {
        confirmpasswordField.type = 'password';
        togglePasswordTwo.classList.remove('bi-eye-slash-fill');
        togglePasswordTwo.classList.add('bi-eye-fill');
    }
});


// *********************************************************************************************



// this code is for file uploading in view document and requests pages

function openFileSelection() {
    document.getElementById('fileInput').click();
}

//   ***************************************************************************************


