$(document).ready(function () {
    // for showing Encounter pop-up on active listing page (Add filter property in background)
    $(".encounter-btn").on("click", function () {
        $(".encounter").show();
    });

    // for showing transfer-request pop-up on pending listing page (Add filter property in background)
    $(".transfer-request-btn").click(function () {
        $(".transfer-request").show();
    });

    // for showing send-link pop-up on every listing page (Add filter property in background)
    $(".send-link-btn").click(function () {
        $(".send-link").show();
    });

    // for showing request-to-admin pop-up on providerProfile Page
    $(".request-admin-btn").click(function () {
        $(".request-to-admin").show();
    });

    // for Hiding Encounter pop-up on active listing page
    $(".hide-popup-btn").on("click", function () {
        $(".pop-up").hide();
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
