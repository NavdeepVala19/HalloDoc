

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
        else if($('.btn-someone').hasClass('btn-active')) {
            $(window).attr('location', '/createSomeoneRequests');
        } else{
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



// **** This code is for Enabling input fields in patientProfile Page and replacing Edit with Save and cancel button

$(document).ready(function () {
    $('#patientProfileEditBtn').click(function () {
        $('.first_name').removeAttr("disabled");
        $('.last_name').removeAttr("disabled");
        $('.date_of_birth').removeAttr("disabled");
        $('.phone').removeAttr("disabled");
        $('.email').removeAttr("disabled");
        $('.street').removeAttr("disabled");
        $('.city').removeAttr("disabled");
        $('.state').removeAttr("disabled");
        $('.zipcode').removeAttr("disabled");

        $('#patientProfileEditBtn').hide();
        $('#patientProfileSubmitBtn').show();
        $('#patientProfileCancelBtn').show();

    })
})