
$(document).ready(function () {
    $('.export-data-to-excel').click(function () {
        $('#exportSearchForm').attr('action', "/search-records/export");
        $('#exportSearchForm').submit();

    })

     $.validator.addMethod(
         "phoneUS",
         function (phone_number, element) {
             return (
                 this.optional(element) ||
                 phone_number.match(
                     /^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/
                 )
             );
         },
         "Please enter a valid phone number."
     );

     $.validator.addMethod(
         "city",
         function (value, element) {
             return value.match(/^[a-zA-Z ,_-]+?$/);
         },
         "Please enter a valid city name."
     );

     $.validator.addMethod(
         "state",
         function (value, element) {
             return value.match(/^[a-zA-Z ,_-]+?$/);
         },
         "Please enter a valid state name."
     );

     $.validator.addMethod(
         "zipcode",
         function (value, element) {
             return value.length == 6 && /\d/.test(value);
         },
         "Please enter a valid zipcode."
     );
     $.validator.addMethod(
         "password",
         function (email, element) {
             return this.optional(element) || email.match(/^\S(.*\S)?$/);
         },
         "Please enter a valid password"
     );
     $.validator.addMethod(
         "lettersUserName",
         function (value, element) {
             return this.optional(element) || /^[a-zA-Z]+$/.test(value);
         },
         "Please enter only letters for your User name."
     );
     $.validator.addMethod(
         "lettersFirstName",
         function (value, element) {
             return this.optional(element) || /^[a-zA-Z]+$/.test(value);
         },
         "Please enter only letters for your first name."
     );

     $.validator.addMethod(
         "emailAddress",
         function (email, element) {
             return (
                 this.optional(element) ||
                 email.match(/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/)
             );
         },
         "Please enter a valid email (format: alphanum@alpha.domain)."
     );

     $.validator.addMethod(
         "lettersLastName",
         function (value, element) {
             return this.optional(element) || /^[a-zA-Z]+$/.test(value);
         },
         "Please enter only letters for your Last name."
     );

     $("#createAdminAccountForm").validate({
         rules: {
             request_status: {
                 required: false,
             },
             patient_name: {
                 required: false,
                 minlength: 1,
             },
             from_date_of_service: {
                 required: false,
             },
             request_type: {
                 required: false,
             },
             to_date_of_service: {
                 required: false,
             },
             provider_name: {
                 required: false,
                 minlength: 1,
             },
             email: {
                 required: false,
                 emailAddress: true,
                 minlength:1
             },
             phone_number: {
                 required: false,
                 phoneUS: true,
             },
         },
         messages: {
             request_status: {
                 required: "Please select status",
             },
             patient_name: {
                 required: "Please enter a patient name",
             },
             from_date_of_service: {
                 required: "Please enter a firstname between 3 and 15 character",
             },
             request_type: {
                 required: "Please enter a lastname between 3 and 15 character",
             },
             to_date_of_service: {
                 required:
                     "Please enter a valid email format (e.g., user@example.com).",
             },
             provider_name: {
                 required:
                     "Please enter a valid provider name",
             },
             email: {
                 required:
                     "Please enter a valid email format (e.g., user@example.com).",
             },
             phone_number: {
                 required:
                     "Please enter a valid email format (e.g., user@example.com).",
             },
         },
         errorElement: "span",
         errorPlacement: function (error, element) {
             error.addClass("errorMsg");
             element.closest(".form-floating").append(error);
         },
         highlight: function (element, errorClass, validClass) {
             $(element).addClass("is-invalid").removeClass("is-valid");
         },
         unhighlight: function (element, errorClass, validClass) {
             $(element).removeClass("is-invalid").addClass("is-valid");
         },
     });
})