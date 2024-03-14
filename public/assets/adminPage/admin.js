$(document).ready(function () {
    $(".cancel-case-btn").click(function () {
        $(".cancel-case").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
        $(".displayPatientName").html($(this).data("patient_name"));

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

    $(".assign-case-btn").click(function () {
        $(".assign-case").show();
        $(".overlay").show();

        $(".physicianRegions").empty();
        $(".requestId").val($(this).data("id"));

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

    $(".block-case-btn").click(function () {
        $(".block-case").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
        $(".displayPatientName").html($(this).data("patient_name"));
    });

    $(".transfer-btn").click(function () {
        $(".transfer-case").show();
        $(".overlay").show();
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

        // $("#closeCase").submit();
    });

    $(".cancel-edit-btn").click(function () {
        $(".phone, .email").attr("disabled", true);

        $(".new-buttons").hide();
        $(".default-buttons").show();
    });

    // Send Orders Page dynamic data fetching
    $(".profession-menu").on("change", function () {
        let profession = $(this).val();
        $(".business-menu").html("<option selected>Buisness</option>");
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
        let profession = $(this).val();
        console.log(profession);

        $(this).val(profession);

        if (profession === "0") {
            // Redirect to all professions URL
            window.location.href = "/partners";
        } else {
            // Redirect based on selected profession
            window.location.href = "/partners/" + profession;
        }
    });

    // Search Vendor based on name
    $(".search-vendor").on("keypress", function (e) {
        if (e.which == 13) {
            $(".vendorSearchForm").submit();
        }
    });

    // Email logs mobile listing
    $(".main-section").click(function () {
        // $(".details").toggle();
        console.log('here')

        // Target the next sibling .more-info element specifically
        $(this).next(".details").toggleClass("active");

        // Close any other open .more-info sections
        $(".details").not($(this).next(".details")).removeClass("active");
    });

    // Cancel History Page
    // Clear all input fields
    $(".clearButton").click(function () {
        $(".empty-fields").val("");
    });

    $(document).on('click', '.action-btn', function () {

        var sibling = $(this).siblings(".actions-menubar:visible").length;

        if (sibling > 0) {
            $(this).siblings(".actions-menubar").hide();
        } else {
            $(this).siblings(".actions-menubar").show();
        }
    })



    $('.request-support-btn').click(function () {
        $('.request-support').show();
    })



    // ************************************* Shivesh *************************************

    // ***************** Fetching regions from regions table ******************
    $.ajax({
        url: "/admin-new",
        type: "GET",
        success: function (data) {
            // Assuming data is an array of reasons
            data.forEach(function (region) {
                $("#listing-region").append(
                    '<option value="' + region.id + '">' + region.region_name + "</option>"
                );
            });
        },
        error: function (error) {
            console.error(error);
        },

    });



    // ***************** Filtering regions from dropdown button ******************

    $('#listing-region').on('change', function () {
        var token = $('meta[name="csrf-token"]').attr('content')
        // Store the selected option's ID
        var tab = $(".nav-link.active").attr('id');
        var words = tab.split("-");
        var activeStatus = words[1];

        var selectedId = $(this).val();

        $.ajax({
            url: "/dropdown-data/",
            type: "POST",
            dataType: 'json',
            data: {
                regionId: selectedId,
                status: activeStatus,
                "_token": token
            },
            success: function (data) {
                $("#dropdown-data-body").html(data.html);
            },
            error: function (error) {
                console.error(error);
            }
        });
    })



    // ********************* Filtering AccountType in User Access Page ***********************

    $('#accountType').on('change', function () {
        var accountType = $(this).val();
        var token = $('meta[name="csrf-token"]').attr('content')

        $.ajax({
            url: "/user-access/filter",
            method: "POST",
            data: {
                accountType: accountType,
                "_token": token
            },
            success: function (response) {
                $('#user-access-table').html(response.html);

            },
            error: function (error) {
                console.error(error);
            }
        })

    })

});



    // Display different roles checkboxes as per the roles selected
    $(".role-selected").on("change", function () {
        let role = $(this).val();
        $.ajax({
            url: "/fetch-roles/" + role,
            type: "GET",
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


