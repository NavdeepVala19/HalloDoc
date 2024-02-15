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

    // for showing Encounter pop-up on active listing page (Add filter property in background)
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
        // $(this).css({ "background-color": "var(--blue)", color: "#fff" });
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
    // $(".conclude-action-btn").click(function () {
    //     console.log($(this).data("case"));
    // });

    // for showing transfer-request pop-up on pending listing page (Add filter property in background)
    $(".transfer-request-btn").click(function () {
        $(".transfer-request").show();
        $(".overlay").show();
    });

    // for showing send-link pop-up on every listing page (Add filter property in background)
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
