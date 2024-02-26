$(document).ready(function () {
    $(".cancel-case-btn").click(function () {
        $(".cancel-case").show();
        $(".overlay").show();

        $(".requestId").val($(this).data("id"));
        $(".displayPatientName").html($(this).data("patient_name"));

        $.ajax({
            url: '/cancel-case', // Update this to your Laravel route
            type: 'GET',
            success: function(data) {
                // Assuming data is an array of reasons
                data.forEach(function(reason) {
                    $('#floatingSelect').append('<option value="' + reason.id + '">' + reason.case_name + '</option>');
                });
            },
            error: function(error) {
                console.error(error);
            }
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

$('.transfer-btn').click(function(){
    $('.transfer-case').show();
    $('.overlay').show();
})

    // changes start from here
    
    



});
