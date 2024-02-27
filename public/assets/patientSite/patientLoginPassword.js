
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

    $('.contact-btn').click(function () {
        $('.new-provider-pop-up').show();
        $('.overlay').show();
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


