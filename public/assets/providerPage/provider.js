$(document).ready(function () {
    // when clicked anywhere outside action-menu -> hide action-menu
    $(document).click(function () {
        $(".action-menu").hide();
    });

    $(document).on("click", ".send-mail-btn", function (event) {
        $(".send-mail").show();
        $(".overlay").show();

        let requestId = $(this).data("requestid");
        let name = $(this).data("name");
        let email = $(this).data("email");

        console.log(requestId);

        $(".requestId").val(requestId);
        $(".displayPatientName").html(name);
        $(".displayPatientEmail").html("(" + email + ")");
    });

    // Showing action menu -> when clicked on action-btn
    $(document).on("click", ".action-btn", function (event) {
        event.stopPropagation();
        $(this).siblings(".action-menu").toggle();
        $(".action-menu").not($(this).next(".action-menu")).hide();
        $(".case-id").val($(this).data("id"));
    });

    // Showing Encounter pop-up on active listing page
    $(".encounter-btn").on("click", function () {
        $(".encounter").show();
        $(".overlay").show();
        $(".case-id").val($(this).data("id"));
    });

    // When housecall-btn is clicked
    $(".housecall-btn").click(function () {
        // Toggle class btn-active
        $(this).toggleClass("btn-active");
        $(".consult-btn").removeClass("btn-active");

        // Store value in hidden inputs
        $(".house_call").val(1);
        $(".consult").val(0);

        // show and hide save button, as per the call selected or not
        $(".encounter-save-btn").toggleClass("houseCallSelected");
        $(".encounter-save-btn").removeClass("consultCallSelected");
    });

    $(".consult-btn").click(function () {
        // Toggle class btn-active
        $(this).toggleClass("btn-active");
        $(".housecall-btn").removeClass("btn-active");

        // Store value in hidden inputs
        $(".house_call").val(0);
        $(".consult").val(1);

        // show and hide save button, as per the call selected or not
        $(".encounter-save-btn").toggleClass("consultCallSelected");
        $(".encounter-save-btn").removeClass("houseCallSelected");
    });

    // Conclude Case Encounter Form - Medical Report
    $(".encounter-popup-btn").click(function () {
        // If medical report is finalized then open encouter pop-up to download the medical report
        $(".requestId").val($(this).data("id"));
        $(".encounter-finalized").show();
        $(".overlay").show();
    });

    // Show transfer-request pop-up on pending listing page
    $(".transfer-btn").click(function () {
        $(".transfer-request").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
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

    // for Hiding Encounter pop-up on active listing page pop-up assign-case
    $(document).on("click", ".hide-popup-btn", function (event) {
        event.preventDefault();

        $(".pop-up").hide();
        $(".overlay").hide();
    });

    // Mobile Listing view (when clicked on any particular case, open details about it)
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

    // Uncheck master checkbox when child-checkbox is changed from selected to unselected
    $(document).on("click", ".child-checkbox", function () {
        if (
            $(".child-checkbox:not(:checked)").length > 0 &&
            $(".master-checkbox").is(":checked")
        ) {
            $(".master-checkbox").prop("checked", false);
        }
        if ($(".child-checkbox:not(:checked)").length == 0) {
            $(".master-checkbox").prop("checked", true);
        }
    });

    // For Send Aggrement Pop-up in pending listing page
    $(document).on("click", ".send-agreement-btn", function () {
        $(".send-agreement").show();
        $(".overlay").show();
        $(".send-agreement-id").val($(this).data("id"));

        $(".agreement-phone-number").val($(this).data("phone_number"));
        $(".agreement-email").val($(this).data("email"));

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
                '<i class="bi bi-circle-fill blue me-2"></i>Concierge'
            );
        } else if ($(this).data("request_type_id") == 4) {
            $(".request-detail").html(
                '<i class="bi bi-circle-fill red  me-2"></i>Buisness'
            );
        }
    });

    // View Uploads File Upload Functionality
    $("#file-upload").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(".upload-label").text(fileName);
    });

    // Provider profile
    $(".reset-password-btn").on("click", function () {
        $(".reset-password-container").hide();
        $(".password-btn-container").show();

        $(".password-field").attr("disabled", false);
    });

    $(".cancel-password-reset").on("click", function () {
        $(".password-field").attr("disabled", true);

        $(".reset-password-container").show();
        $(".password-btn-container").hide();
    });

    // ---------------- RESET FORMS WHEN POP-UPs ARE CLOSED ----------------------

    $(".sendMailCancel").click(function () {
        $("#sendMailForm").trigger("reset");
        $("#sendMailForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });
    // Reset Form when pop-up is closed
    $(".providerTransferCancel").click(function () {
        $("#providerTransferCase").trigger("reset");
        $("#providerTransferCase").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // Reset Provider Send Link form on Closing pop-up
    $(".providerSendLinkCancel").click(function () {
        $("#providerSendLinkForm").trigger("reset");
        $("#providerSendLinkForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // Provider Send Agreement reset form when pop-up is closed
    $(".providerSendAgreementClose").click(function () {
        // $("#providerSendLinkForm").trigger("reset");
        $("#providerSendAgreement").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });
});
