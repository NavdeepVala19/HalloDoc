$(document).ready(function () {
    // Show cancel-case pop-up
    $(document).on("click", ".cancel-case-btn", function () {
        $(".cancel-case").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
        $(".displayPatientName").html($(this).data("patient_name"));
        $("#floatingSelect")
            .empty()
            .append('<option value="" selected disabled>Reasons</option>');

        // Fetch cancel-case reasons from database using AJAX calls
        $.ajax({
            url: "/cancel-case",
            type: "GET",
            success: function (data) {
                data.forEach(function (reason) {
                    $("#floatingSelect").append(
                        '<option value="' +
                            reason.id +
                            '">' +
                            reason.case_name +
                            "</option>"
                    );
                });
            },
            error: function (error) {
                console.error(error);
            },
        });
    });

    $(document).on("click", ".assign-case-btn", function () {
        $(".assign-case").show();
        $(".overlay").show();

        $(".physicianRegions").empty();
        $(".requestId").val($(this).data("id"));
        $(".physicianRegions")
            .empty()
            .append("<option selected disabled>Regions</option>");

        // Assign Case Pop-up -> populate select menu with all physician Regions available, admin can filter through these regions
        $.ajax({
            url: "/physician-regions",
            type: "GET",
            success: function (data) {
                data.forEach(function (region) {
                    $(".physicianRegions").append(
                        '<option value="' +
                            region.id +
                            '">' +
                            region.region_name +
                            "</option>"
                    );
                });
            },
            error: function (error) {
                console.log(error);
            },
        });
    });

    $(document).on("change", ".physicianRegions", function () {
        $physician = $(this).val();
        $(".selectPhysician").empty();
        $.ajax({
            url: "/physician/" + $physician,
            type: "GET",
            success: function (data) {
                if (data.length == 0) {
                    $(".selectPhysician").append(
                        $(
                            "<option disabled>No physicians for these Region</option>"
                        )
                    );
                }

                data.forEach(function (physician) {
                    $(".selectPhysician").append(
                        $("<option>", {
                            value: physician.id,
                            text:
                                physician.first_name +
                                " " +
                                physician.last_name,
                        })
                    );
                });
            },
            error: function (error) {
                console.error(error);
            },
        });
    });

    // don't show the physician who transfered case to admin (as we do not want to assign the case again to him/her)
    $(".physicianRegionsTransferCase").on("change", function () {
        $region = $(this).val();
        $requestId = $(".requestId").val();
        $(".selectPhysician").empty();
        $.ajax({
            url: "/newPhysicians/" + $requestId + "/" + $region,
            type: "GET",
            success: function (data) {
                if (data.length == 0) {
                    $(".selectPhysician").append(
                        $(
                            "<option disabled>No physicians for these Region</option>"
                        )
                    );
                }
                data.forEach(function (physician) {
                    $(".selectPhysician").append(
                        $("<option>", {
                            value: physician.id,
                            text:
                                physician.first_name +
                                " " +
                                physician.last_name,
                        })
                    );
                });
            },
            error: function (error) {
                console.error(error.responseJSON);
            },
        });
    });

    // Show block-case pop-up
    $(document).on("click", ".block-case-btn", function () {
        $(".block-case").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
        $(".displayPatientName").html($(this).data("patient_name"));
    });

    // Show Transfer-case pop-up
    $(document).on("click", ".transfer-btn", function () {
        $(".transfer-case").show();
        $(".overlay").show();

        $(".physicianRegionsTransferCase").empty();
        $(".requestId").val($(this).data("id"));
        $(".physicianRegionsTransferCase")
            .empty()
            .append("<option selected disabled>Regions</option>");

        // populate select menu with all physician Regions available, admin can filter through these regions
        $.ajax({
            url: "/physician-regions",
            type: "GET",
            success: function (data) {
                data.forEach(function (region) {
                    $(".physicianRegionsTransferCase").append(
                        '<option value="' +
                            region.id +
                            '">' +
                            region.region_name +
                            "</option>"
                    );
                });
            },
            error: function (error) {
                console.log(error);
            },
        });
    });

    $(document).on("click", ".clear-btn", function () {
        $(".clear-case").show();
        $(".overlay").show();

        $(".request_id").val($(this).data("id"));
    });

    $(".edit-case-btn").click(function () {
        $(
            ".firstName, .lastName, .dob, .phoneNumber, .email, .patientNotes"
        ).removeAttr("disabled");
        $(".edit-case-btn").hide();
        $(".save-case-btn").show();
        $(".cancel-edit-btn").show();
    });

    $(".cancel-edit-btn").click(function () {
        $(
            ".firstName, .lastName, .dob, .phoneNumber, .email, .patientNotes"
        ).attr("disabled", true);
        $(".edit-case-btn").show();
        $(".save-case-btn").hide();
        $(".cancel-edit-btn").hide();
    });

    $(".edit-btn").click(function () {
        $(".phone").removeAttr("disabled");
        $(".email").removeAttr("disabled");

        $(".new-buttons").show();
        $(".default-buttons").hide();
    });

    $(".save-edit-btn").click(function () {
        $(".phone, .email").attr("disabled", false);

        $(".new-buttons").hide();
        $(".default-buttons").show();
    });

    $(".cancel-edit-btn").click(function () {
        $(".phone, .email").attr("disabled", true);

        $(".new-buttons").hide();
        $(".default-buttons").show();
    });

    // Send Orders Page dynamic data fetching
    $(".profession-menu").on("change", function () {
        let profession = $(this).val();
        $(".business-menu").html("<option selected disabled>Buisness</option>");
        $.ajax({
            url: "/fetch-business/" + profession,
            type: "GET",
            success: function (data) {
                // data -> array of all business with given profession
                data.forEach(function (entry) {
                    // entry -> single business
                    $(".business-menu").append(
                        '<option value="' +
                            entry.id +
                            '">' +
                            entry.vendor_name +
                            "</option>"
                    );
                });
            },
            error: function (error) {
                console.error(error);
            },
        });
    });
    $(".business-menu").on("change", function () {
        let business = $(this).val();
        $.ajax({
            url: "/fetch-business-data/" + business,
            type: "GET",
            success: function (data) {
                $(".business_contact").val(data.business_contact);
                $(".email").val(data.email);
                $(".fax_number").val(data.fax_number);
            },
            error: function (error) {
                console.error(error);
            },
        });
    });

    // Partners page filter partners based on profession selected
    $(".select-profession").on("change", function () {
        $(this).closest("form").submit();
    });
    // View Uploads -> when Send Mail button is clicked show the loader
    $(".sendMailBtn").click(function () {
        $("#loading-icon").fadeIn("slow");
    });

    // Search Vendor based on name
    $(".search-vendor").on("keypress", function (e) {
        if (e.which == 13) {
            $(".vendorSearchForm").submit();
        }
    });

    // Email logs mobile listing
    $(".main-section").click(function () {
        // Target the next sibling .more-info element specifically
        $(this).next(".details").toggleClass("active");

        $(".details").not($(this).next(".details")).removeClass("active");
    });

    // Cancel History Page
    // Clear all input fields
    $(".clearButton").click(function () {
        $(".empty-fields").val("");
    });

    $(".request-support-btn").click(function () {
        $(".request-support").show();
        $(".overlay").show();
    });

    // ---------------- RESET FORMS WHEN POP-UPs ARE CLOSED ----------------------
    // Reset Cancel Case Form when pop-up is closed
    $(document).on("click", ".cancleCaseClose", function () {
        $("#cancelCaseForm").trigger("reset");
        $("#cancelCaseForm").validate().resetForm();
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-valid"
        );
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-invalid"
        );
    });

    // Reset Admin Assign Case pop-up when it's closed
    $(document).on("click", ".adminAssignCancel", function () {
        $(".pop-up .selectPhysician")
            .empty()
            .append("<option selected disabled>Select physicians</option>");
        $("#adminAssignCase").trigger("reset");
        $("#adminAssignCase").validate().resetForm();
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-valid"
        );
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-invalid"
        );
    });

    // Reset Form when Transfer case pop-up is closed
    $(".adminTransferCancel").click(function () {
        $(".pop-up .selectPhysician")
            .empty()
            .append(
                '<option value="" selected disabled>Select Physician</option>'
            );
        $("#adminTransferRequest").trigger("reset");
        $("#adminTransferRequest").validate().resetForm();
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-valid"
        );
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-invalid"
        );
    });

    // Reset Form when block case pop-up is closed
    $(".blockCaseCancel").click(function () {
        $("#adminBlockCase").trigger("reset");
        $("#adminBlockCase").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // Reset Admin Send Link form on Closing pop-up
    $(".adminSendLinkClose").click(function () {
        $("#adminSendLinkForm").trigger("reset");
        $("#adminSendLinkForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // Admin Send Agreement reset form when pop-up is closed
    $(".adminSendAgreementClose").click(function () {
        // $("#providerSendLinkForm").trigger("reset");
        $("#adminSendAgreement").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // ************************************* Shivesh *************************************
    $(document).on("click", ".action-btn", function () {
        var sibling = $(this).siblings(".actions-menubar:visible").length;

        if (sibling > 0) {
            $(this).siblings(".actions-menubar").hide();
        } else {
            $(this).siblings(".actions-menubar").show();
        }
    });

    // ***** This code is for getting filtername in admin dashboard (all,patient,family,concierge,business) *****

    var pathname = window.location.pathname;
    var url = pathname.split("/");
    var activeCategory = url[3];

    if (!activeCategory) {
        $(".btn-all.filter-btn").addClass("active-filter");
    }
    var filterBtns = $(".filter-btn");
    filterBtns.map(function (index, element) {
        if (element.dataset.category == activeCategory) {
            $(element).addClass("active-filter");
        }
    });

    // ** Fetching regions from regions table *
    $.ajax({
        url: "/admin-new",
        type: "GET",
        success: function (data) {
            data.forEach(function (region) {
                $(".listing-region").append(
                    // '<option value="' +
                    // region.id +
                    //     '">' +
                    // region.region_name +
                    // "</option>"

                    '<option value="' +
                        region.id +
                        '"' +
                        (selectedRegionId == region.id ? " selected" : "") +
                        ">" +
                        region.region_name +
                        "</option>"

                    // '<option value="' + region.id + '"' + (region.region_name == selectedRegionName ? selected : "") + '>' + region.region_name + '</option>'

                    // `<option value="${region.id}"  ${region.region_name} == ${selectedRegionName} ? 'selected' : ''>${region.region_name}</option>`

                    // `<option value="${region.id}" ${region.region_name} == ${selectedRegionName} ? selected : ''>${region.region_name} ${selectedRegionName}</option>`

                    // `<option value="${region.id}" ${region.id == selectedRegionId ? selected : ''}>${region.region_name}</option>`
                );
            });
        },
        error: function (error) {
            console.error(error);
        },
    });
});

// Display different roles checkboxes as per the roles selected
$(".role-selected").on("change", function () {
    let role = $(this).val();
    $.ajax({
        url: "/fetch-roles/" + role,
        method: "GET",
        success: function (data) {
            $(".menu-section").empty();
            data.forEach(function (menu) {
                let checkBox = `<div class="form-check">
                    <input class="form-check-input" name="menu_checkbox[]" value=${menu.id} type="checkbox"
                        id="menu_check_${menu.id}">
                        ${menu.name}
                    </label>
                </div>`;
                $(".menu-section").append(checkBox);
            });
        },
        error: function (error) {
            console.error(error);
        },
    });
});

// **** Fetching roles from role table ****
$.ajax({
    url: "/admin-account-role",
    type: "GET",
    success: function (data) {
        data.forEach(function (role) {
            $("#listing_role_admin_Account").append(
                '<option value="' +
                    role.id +
                    '" class="role_name" >' +
                    role.name +
                    "</option>"
            );
        });
    },
    error: function (error) {
        console.error(error);
    },
});
