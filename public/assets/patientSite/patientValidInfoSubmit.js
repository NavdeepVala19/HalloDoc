$(document).ready(function () {

    // *** This code is for showing request submit pop in family/concierge/business

    $("#back-btn").click(function () {
        localStorage.setItem("popupShown", "true");
    });

    $("#cancel-btn").click(function () {
        localStorage.setItem("popupShown", "true");
    });

    // Check if the popup was already shown
    if (localStorage.getItem("popupShown") == "false") {
        // Show the popup if not shown before
        $("#validDetailsPopup").hide();
        $(".overlay").hide();
    } else {
        $("#validDetailsPopup").show();
        $(".overlay").show();
    }

    // Attach an event listener to the "OK" button in the popup
    $("#closePopupBtn").on("click", function () {
        // When clicked, hide the popup
        $("#validDetailsPopup").hide();
        $(".overlay").hide();

        // And set a flag in localStorage indicating the popup was shown
        localStorage.setItem("popupShown", "false");
    });


});

    // **** This code is for hiding pop-up button in family/concierge/business page ****

    $(".submit-valid-details-ok-btn").click(function () {
        $(".submit-valid-details").show();
        $(".overlay").show();
    });

    //  *****