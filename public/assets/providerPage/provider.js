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
    });

    $(".housecall-btn").click(function () {
        $(this).toggleClass("btn-active");
        $(".consult-btn").removeClass("btn-active");
        $(".time-dropdown").show();
    });

    $(".consult-btn").click(function () {
        $(this).toggleClass("btn-active");
        $(".housecall-btn").removeClass("btn-active");
        $(".time-dropdown").hide();
    });

    $(".encounter-save-btn").click(function () {
        if ($(".consult-btn").hasClass("btn-active")) {
            console.log($(".case-id").val());
        } else if ($(".housecall-btn").hasClass("btn-active")) {
        }
    });

    // Conclude Case Encounter Form - Medical Report
    $(".finalize-btn").click(function () {
        // let id = $(this).data("id");
        // window.print();
        // $(window).attr("location", "/provider/conclude");
        // $(window).on("afterprint", function () {
        //     $(window).attr("location", "/provider/conclude");
        // });
        // window.addEventListener("afterprint", function (event) {
        //     // Redirect to specific URL after printing only if the print button was clicked
        //     window.location.href = "/provider/conclude"; // Replace with your redirect URL
        // });
        // console.log("clicked");
    });

    // for showing transfer-request pop-up on pending listing page
    $(".transfer-btn").click(function () {
        $(".transfer-request").show();
        $(".overlay").show();
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

    // for Hiding Encounter pop-up on active listing page
    $(".hide-popup-btn").on("click", function (event) {
        event.preventDefault();
        $(".pop-up").hide();
        $(".overlay").hide();
    });

    // for Provider Transfer Request pop-up - Pending Page

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
                '<i class="bi bi-circle-fill red me-2"></i>Business'
            );
        } else if ($(this).data("request_type_id") == 4) {
            $(".request-detail").html(
                '<i class="bi bi-circle-fill blue me-2"></i>Concierge'
            );
        }
    });


});



// var canvas = document.getElementById('signatureCanvas');
//     var context = canvas.getContext('2d');
//     var drawing = false;

//     // Start drawing when mouse is pressed
//     $('#signatureCanvas').mousedown(function (e) {
//         drawing = true;
//         draw(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, false);
//     });

//     // Stop drawing when mouse is released
//     $(document).mouseup(function () {
//         drawing = false;
//         context.beginPath();
//     });

//     // Draw as mouse moves
//     $('#signatureCanvas').mousemove(function (e) {
//         if (drawing) {
//             draw(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
//         }
//     });

//     // Clear the canvas
//     $('#clearCanvas').click(function () {
//         context.clearRect(0, 0, canvas.width, canvas.height);
//     });

//     // Handle the creation of the signature
//     $('.create-signature-btn').click(function () {
//         // You can save the signature as an image or any other desired format
//         var signatureImage = canvas.toDataURL("image/png");
//         // You can then handle the image as needed, such as uploading it to the server
//         console.log(signatureImage);
//     });

//     // Helper function to draw on the canvas
//     function draw(x, y, isDown) {
//         if (isDown) {
//             context.beginPath();
//             context.strokeStyle = 'black';
//             context.lineWidth = 2;
//             context.lineJoin = 'round';
//             context.moveTo(lastX, lastY);
//             context.lineTo(x, y);
//             context.closePath();
//             context.stroke();
//         }
//         lastX = x;
//         lastY = y;
//     }