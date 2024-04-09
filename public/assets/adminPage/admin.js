$(document).ready(function () {
    $(".cancel-case-btn").click(function () {
        $(".cancel-case").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
        $(".displayPatientName").html($(this).data("patient_name"));
        $("#floatingSelect")
            .empty()
            .append('<option value="" selected disabled>Reasons</option>');

        $.ajax({
            url: "/cancel-case",
            type: "GET",
            success: function (data) {
                // Assuming data is an array of reasons
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
    // Reset Cancel Case Form when pop-up is closed
    $(".cancleCaseClose").click(function () {
        $("#cancelCaseForm").trigger("reset");
        $("#cancelCaseForm").validate().resetForm();
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-valid"
        );
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-invalid"
        );
    });

    $(".assign-case-btn").click(function () {
        $(".assign-case").show();
        $(".overlay").show();

        $(".physicianRegions").empty();
        $(".requestId").val($(this).data("id"));
        $(".physicianRegions")
            .empty()
            .append(
                '<option value="" selected disabled>Narrow Search by Region</option>'
            );

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

    $(".adminAssignCancel").click(function () {
        $(".pop-up .selectPhysician")
            .empty()
            .append(
                '<option value="" selected disabled>Select Physician</option>'
            );
        $("#adminAssignCase").trigger("reset");
        $("#adminAssignCase").validate().resetForm();
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-valid"
        );
        $(".pop-up form .form-control, .pop-up form .form-select").removeClass(
            "is-invalid"
        );
    });

    $(".physicianRegions").on("change", function () {
        $physician = $(this).val();
        $(".selectPhysician").empty();
        $.ajax({
            url: "/physician/" + $physician,
            type: "GET",
            success: function (data) {
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

    $(".block-case-btn").click(function () {
        $(".block-case").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
        $(".displayPatientName").html($(this).data("patient_name"));
    });

    $(".transfer-btn").click(function () {
        $(".transfer-case").show();
        $(".overlay").show();

        $(".physicianRegionsTransferCase").empty();
        $(".requestId").val($(this).data("id"));
        $(".physicianRegionsTransferCase")
            .empty()
            .append(
                '<option value="" selected disabled>Narrow Search by Region</option>'
            );

        // Assign Case Pop-up -> populate select menu with all physician Regions available, admin can filter through these regions
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

    $(".clear-btn").click(function () {
        $(".clear-case").show();
        $(".overlay").show();

        $(".request_id").val($(this).data("id"));
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

    // Reset Admin Send Link form on Closing pop-up
    $(".adminSendLinkClose").click(function () {
        $("#adminSendLinkForm").trigger("reset");
        $("#adminSendLinkForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
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

    // Admin Send Agreement reset form when pop-up is closed
    $(".adminSendAgreementClose").click(function () {
        // $("#providerSendLinkForm").trigger("reset");
        $("#adminSendAgreement").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // ************************************* Shivesh *************************************

    $("#admin-info-cancel-btn").on("click", function () {
        $(".admin_first_name").attr("disabled");
        $(".admin_last_name").attr("disabled");
        $(".admin_email").attr("disabled");
        $(".admin_confirm_email").attr("disabled");
        $(".phone").attr("disabled");

        $("#adminEditBtn1").show();
        $(".admin-info-btns").hide();
    });

    $("#admin-mail-cancel-btn").on("click", function () {
        $(".admin_add1").attr("disabled");
        $(".admin_add2").attr("disabled");
        $(".city").attr("disabled");
        $(".admin_state").attr("disabled");
        $(".admin_zipcode").attr("disabled");
        $(".admin_alt_phone").attr("disabled");

        $("#adminEditBtn2").show();
        $(".admin-mail-info-btns").hide();
    });

    $("#adminAccEditBtn").on("click", function () {
        $(".admin_user_name").removeAttr("disabled");
        $("#status-select").removeAttr("disabled");
        $("#listing_role_admin_Account").removeAttr("disabled");

        $("#adminAccEditBtn").hide();
        $(".admin-acc-btns").show();
    });

    $("#adminEditBtn1").on("click", function () {
        $(".admin_first_name").removeAttr("disabled");
        $(".admin_last_name").removeAttr("disabled");
        $(".admin_email").removeAttr("disabled");
        $(".admin_confirm_email").removeAttr("disabled");
        $(".phone").removeAttr("disabled");

        $("#adminEditBtn1").hide();
        $(".admin-info-btns").show();
    });

    $("#adminEditBtn2").on("click", function () {
        $(".admin_add1").removeAttr("disabled");
        $(".admin_add2").removeAttr("disabled");
        $(".city").removeAttr("disabled");
        $(".admin_state").removeAttr("disabled");
        $(".admin_zipcode").removeAttr("disabled");
        $(".admin_alt_phone").removeAttr("disabled");
        $("#listing_state_admin_account").removeAttr("disabled");

        $("#adminEditBtn2").hide();
        $(".admin-mail-info-btns").show();
    });

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

    // ************************************************************************************

    // ***************** Fetching regions from regions table ******************
    $.ajax({
        url: "/admin-new",
        type: "GET",
        success: function (data) {
            data.forEach(function (region) {
                $(".listing-region").append(
                    '<option value="' +
                        region.id +
                        '">' +
                        region.region_name +
                        "</option>"
                );
            });
        },
        error: function (error) {
            console.error(error);
        },
    });
    // ************************************************************************************

    // ***************** Filtering regions from dropdown button ******************

    // $(".listing-region").on("change", function () {
    //     var token = $('meta[name="csrf-token"]').attr("content");
    //     // Store the selected option's ID
    //     var tab = $(".nav-link.active").attr("id");
    //     var words = tab.split("-");
    //     var activeStatus = words[1];

    //     var selectedId = $(this).val();

    //     $.ajax({
    //         url: "/dropdown-data",
    //         type: "POST",
    //         dataType: "json",
    //         data: {
    //             regionId: selectedId,
    //             status: activeStatus,
    //             _token: token,
    //         },
    //         success: function (data) {
    //             $("#dropdown-data-body").html(data.html);
    //         },
    //         error: function (error) {
    //             console.error(error);
    //         },
    //     });
    // });
    // ************************************************************************************

    // ********************* Filtering AccountType in User Access Page ***********************

    // $('#accountType').on('change', function () {
    //     var accountType = $(this).val();

    //     var token = $('meta[name="csrf-token"]').attr('content')

    //     $.ajax({
    //         url: "/user-access/filter",
    //         method: "POST",
    //         data: {
    //             accountType: accountType,
    //             "_token": token
    //         },
    //         success: function (response) {
    //             $('#user-access-table').html(response.html);
    //         },
    //         error: function (error) {
    //             console.error(error);
    //         }
    //     })
    // })
});

// ************************************************************************************

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

// **** Fetching regions from regions table ****
$.ajax({
    url: "/admin-account-state",
    type: "GET",
    success: function (data) {
        data.forEach(function (region) {
            $("#listing_state_admin_account").append(
                '<option value="' +
                    region.id +
                    '" class="state-name" >' +
                    region.region_name +
                    "</option>"
            );
        });
    },
    error: function (error) {
        console.error(error);
    },
});

// *** End of fetching regions from regions table ***

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

// *** End of Fetching roles from role table ***
