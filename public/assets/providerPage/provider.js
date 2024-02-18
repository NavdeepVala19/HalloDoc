$(document).ready(function () {
    $(document).click(function () {
        $(".action-menu").hide();
        // $(".pop-up").hide(); //  also do event.stopPropagation in particular button clicked
        // $(".send-link").hide(); //  also do event.stopPropagation in particular button clicked
        // $(".overlay").hide();
    });
    // for showing action menu in new listing page
    $(".action-btn").click(function (event) {
        $(this).siblings(".action-menu").toggle();
        $(".action-menu").not($(this).next(".action-menu")).hide();
        $(".case-id").val($(this).data("id"));

        event.stopPropagation();
    });

    // for showing Encounter pop-up on active listing page
    $(".encounter-btn").on("click", function () {
        $(".encounter").show();
        $(".overlay").show();
    });

    $(".housecall-btn").click(function () {
        $(this).toggleClass("btn-active");
        $(".consult-btn").removeClass("btn-active");
        $(".time-dropdown").show();
    });

    $(".consult-btn").click(function () {
        $(this).toggleClass("btn-active");
        $(".housecall-btn").removeClass("btn-active");
        $(".time-dropdown").hide();
    });

    $(".encounter-save-btn").click(function () {
        if ($(".consult-btn").hasClass("btn-active")) {
            console.log($(".case-id").val());
        } else if ($(".housecall-btn").hasClass("btn-active")) {
        }
    });

    // Conclude Case Encounter Form - Medical Report
    $(".finalize-btn").click(function () {
        // let id = $(this).data("id");
        // window.print();
        // $(window).attr("location", "/provider/conclude");

        // $(window).on("afterprint", function () {
        //     $(window).attr("location", "/provider/conclude");
        // });

        // window.addEventListener("afterprint", function (event) {
        //     // Redirect to specific URL after printing only if the print button was clicked

        //     window.location.href = "/provider/conclude"; // Replace with your redirect URL
        // });

        // console.log("clicked");
    });

    // for showing transfer-request pop-up on pending listing page
    $(".transfer-btn").click(function () {
        $(".transfer-request").show();
        $(".overlay").show();
    });

    // for showing send-link pop-up on every listing page
    $(".send-link-btn").click(function (event) {
        $(".send-link").show();
        $(".overlay").show();
    });

    // for showing request-to-admin pop-up on providerProfile Page
    $(".request-admin-btn").click(function () {
        $(".request-to-admin").show();
        $(".overlay").show();
    });

    // for Hiding Encounter pop-up on active listing page
    $(".hide-popup-btn").on("click", function () {
        $(".pop-up").hide();
        $(".overlay").hide();
    });

    // for Provider Transfer Request pop-up - Pending Page

    // Mobile Listing view
    $(".mobile-list").on("click", function () {
        // Target the next sibling .more-info element specifically
        $(this).next(".more-info").toggleClass("active");

        // Close any other open .more-info sections
        $(".more-info").not($(this).next(".more-info")).removeClass("active");
    });
});
