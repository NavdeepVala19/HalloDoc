$(document).ready(function () {
    $(".cancel-case-btn").click(function () {
        $(".cancel-case").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
        $(".displayPatientName").html($(this).data("patient_name"));
    });

    $(".assign-case-btn").click(function () {
        $(".assign-case").show();
        $(".overlay").show();
    });

    $(".block-case-btn").click(function () {
        $(".block-case").show();
        $(".overlay").show();
    });
});
