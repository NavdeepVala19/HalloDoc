

$(document).ready(function () {


    // **** This code is for create a new request in patient dashboard page  *****
    $('.create-btn').click(function () {
        $('.new-request').show();
        $('.overlay').show();
    })

    //  ********************************************************************************************


    // ************************** This code is for create new request pop-up  ***********************

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
        }
        else if ($('.btn-someone').hasClass('btn-active')) {
            $(window).attr('location', '/createSomeoneRequests');
        } else {
            alert('please select "Me" or "SomeOne Else"');
        }
    })

    //  ********************************************************************************************



    // **** This code is for hiding pop-up button in family/concierge/business page ****

    $('.submit-valid-details-ok-btn').click(function () {
        $('.submit-valid-details').show();
        $('.overlay').show()
    })

    //  ********************************************************************************************





    // **** This code is for showing input password and hide it when click on eye icon ****

    $('.person-eye').click(function () {
        const passwordField = $('#exampleInputPassword1');
        if (passwordField.prop('type') === 'password') {
            passwordField.prop('type', 'text');
            $(this).removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
        } else {
            passwordField.prop('type', 'password');
            $(this).removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
        }
    });

    $('.person-eye-two').click(function () {
        const confirmpasswordField = $('#exampleInputPassword2');
        if (confirmpasswordField.prop('type') === 'password') {
            confirmpasswordField.prop('type', 'text');
            $(this).removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
        } else {
            confirmpasswordField.prop('type', 'password');
            $(this).removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
        }
    });

})
// ***************************************************************************************



// **** This code is for patient view documents checkboxes ****

$(".master-checkbox").on("click", function () {
    if ($(this).is(":checked", true)) {
        $(".child-checkbox").prop("checked", true);
    } else {
        $(".child-checkbox").prop("checked", false);
    }
});

// *********************************************************




// **** This code is for show file name********
// 
$('.file-input').change(function (e) {
    const filename = e.target.files[0].name;
    $("#demo").text(filename);
});

// ********************************************






// **** This is use for showing agreement cancel pop-up ****

$(document).ready(function () {
    $('.cancel').click(function () {
        $('.cancel-pop-up').show();
    })

})

// *********************************************************




// **** This code is for file uploading in view document and requests pages ****

function openFileSelection() {
    document.getElementById('fileInput').click();
}
//   ****************************************************************************





// **** This code is for patientDashboard accordion menu ******

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}
//   ****************************************************************************



$(document).ready(function () {

    $('#back-btn').click(function () {
        localStorage.setItem("popupShown", "true");
    });

    $('#cancel-btn').click(function () {
        localStorage.setItem("popupShown", "true");
    });

    // Check if the popup was already shown
    if (localStorage.getItem("popupShown") == "false") {
        // Show the popup if not shown before
        $("#validDetailsPopup").hide();
        $('.overlay').hide()

    } else {
        $("#validDetailsPopup").show();
        $('.overlay').show()
    }

    // Attach an event listener to the "OK" button in the popup
    $("#closePopupBtn").on("click", function () {
        // When clicked, hide the popup
        $("#validDetailsPopup").hide();
        $('.overlay').hide()

        // And set a flag in localStorage indicating the popup was shown
        localStorage.setItem("popupShown", "false");
    });
});


$(document).ready(function () {

    $('#patientProfileEditForm').validate({ // initialize the plugin
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            last_name: {
                required: true,
                minlength: 5
            },
            date_of_birth: {
                required: true,
                minlength: 5
            },
            phone_number: {
                required: true,
                regex: /^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/,
            },
            email: {
                required: true,
                email: true
            },
            street: {
                required: true,
                minlength: 5,
                maxlength: 30,
            },
            city: {
                required: true,
                minlength: 5,
                maxlength: 30,
            },
            state: {
                required: true,
                minlength: 5,
                maxlength: 30,
            },
            zipcode: {
                required: true,
                zipcodeLength: true,
            },
        },
        messages: {
            email: {
                required: "Please enter your email.",
                email: "Please enter a valid email address."
            },
        },
    });

});


