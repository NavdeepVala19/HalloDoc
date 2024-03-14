
$(document).ready(function () {
    $('.export-data-to-excel').click(function () {

        $('#exportSearchForm').attr('action', "/search-records/export");
        $('#exportSearchForm').submit();

        // let status = $('.status-type').val();
        // let patient_name = $('.patient-name').val();
        // let request_type = $('.request-type').val();
        // let from_date_of_service = $('.from-date-of-service').val();
        // let to_date_of_service = $('.to-date-of-service').val();
        // let provider_name = $('.provider-name').val();
        // let email = $('.email').val();
        // let phone_number = $('.phone-number').val();

        // $('#exportSearchForm').find('input[name="status"]').val($('.status-type').val());

        // $.ajax(
        //     {
        //         url: "/search-records/export",
        //         type: "GET",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             status: status,
        //             patient_name: patient_name,
        //             request_type: request_type,
        //             from_date_of_service: from_date_of_service,
        //             to_date_of_service: to_date_of_service,
        //             provider_name: provider_name,
        //             email: email,
        //             phone_number: phone_number,
        //         },
        //         success: function (data) {
        //             console.log('Download successful!');
        //         },
        //         error: function (error) {
        //             console.error(error);
        //         }
        //     }
        // )

    })
})