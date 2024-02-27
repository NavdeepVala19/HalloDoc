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

    $(".profession-menu").on("change", function () {
        let profession = $(this).val();
        console.log(profession);
        $.ajax({
            url: "/fetch-business/" + profession,
            type: "GET",
            // data: {
            //     professionId: profession,
            //     // _token: "{{ csrf_token() }}",
            // },
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
    $('.business-menu').on('change', function(){
        
    });
});
