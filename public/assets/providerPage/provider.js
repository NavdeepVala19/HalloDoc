$(document).ready(function () {
    $(document).click(function () {
        $(".action-menu").hide();
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
        $(".case-id").val($(this).data("id"));
    });

    $(".housecall-btn").click(function () {
        $(this).toggleClass("btn-active");
        $(".consult-btn").removeClass("btn-active");

        $(".house_call").val(1);
        $(".consult").val(0);

        $(".encounter-save-btn").toggleClass("houseCallSelected");
        $(".encounter-save-btn").removeClass("consultCallSelected");
    });

    $(".consult-btn").click(function () {
        $(this).toggleClass("btn-active");
        $(".housecall-btn").removeClass("btn-active");

        $(".house_call").val(0);
        $(".consult").val(1);

        $(".encounter-save-btn").toggleClass("consultCallSelected");
        $(".encounter-save-btn").removeClass("houseCallSelected");
    });

    // Conclude Case Encounter Form - Medical Report
    $(".encounter-popup-btn").click(function () {
        $(".requestId").val($(this).data("id"));

        $(".encounter-finalized").show();
        $(".overlay").show();
    });

    // for showing transfer-request pop-up on pending listing page
    $(".transfer-btn").click(function () {
        $(".transfer-request").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
    });
    // Reset Form when pop-up is closed
    $(".providerTransferCancel").click(function () {
        $("#providerTransferCase").trigger("reset");
        $("#providerTransferCase").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // for showing send-link pop-up on every listing page
    $(".send-link-btn").click(function (event) {
        $(".send-link").show();
        $(".overlay").show();
    });

    // Reset Provider Send Link form on Closing pop-up
    $(".providerSendLinkCancel").click(function () {
        $("#providerSendLinkForm").trigger("reset");
        $("#providerSendLinkForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // for showing request-to-admin pop-up on providerProfile Page
    $(".request-admin-btn").click(function () {
        $(".request-to-admin").show();
        $(".overlay").show();
    });

    // for Hiding Encounter pop-up on active listing page pop-up assign-case
    $(".hide-popup-btn").click(function (event) {
        event.preventDefault();

        $(".pop-up").hide();
        $(".overlay").hide();
    });

    // Mobile Listing view
    $(".mobile-list").on("click", function () {
        // Target the next sibling .more-info element specifically
        $(this).next(".more-info").toggleClass("active");

        // Close any other open .more-info sections
        $(".more-info").not($(this).next(".more-info")).removeClass("active");
    });

    // For selection of all checkbox when master checkbox is clicked in viewUploads page
    $(".master-checkbox").on("click", function () {
        if ($(this).is(":checked", true)) {
            $(".child-checkbox").prop("checked", true);
        } else {
            $(".child-checkbox").prop("checked", false);
        }
    });

    // For Send Aggrement Pop-up in pending listing page
    $(".send-agreement-btn").on("click", function () {
        $(".send-agreement").show();
        $(".overlay").show();
        $(".send-agreement-id").val($(this).data("id"));

        $(".agreement-phone-number").val($(this).data("phone_number"));
        $(".agreement-email").val($(this).data("email"));

        // console.log($(this).data("request_type_id"));
        if ($(this).data("request_type_id") == 1) {
            $(".request-detail").html(
                '<i class="bi bi-circle-fill green me-2"></i>Patient'
            );
        } else if ($(this).data("request_type_id") == 2) {
            $(".request-detail").html(
                '<i class="bi bi-circle-fill yellow me-2"></i>Family/Friend'
            );
        } else if ($(this).data("request_type_id") == 3) {
            $(".request-detail").html(
                '<i class="bi bi-circle-fill red me-2"></i>Concierge'
            );
        } else if ($(this).data("request_type_id") == 4) {
            $(".request-detail").html(
                '<i class="bi bi-circle-fill blue me-2"></i>Buisness'
            );
        }
    });
    // View Uploads File Upload Functionality
    $("#file-upload").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(".upload-label").text(fileName);
    });
});
