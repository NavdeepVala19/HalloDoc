$(document).ready(function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                $('#lat').val(lat);
                $('#lng').val(lng);

                var token = $('meta[name="csrf-token"]').attr("content");
                // Send the location to the server using an AJAX request
            //     $.ajax({
            //         url: "/adminLoggedIn",
            //         type: "POST",
            //         data: {
            //             lat: lat,
            //             lng: lng,
            //             token:token,
            //         },
            //         // headers: {
            //         //     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
            //         //         "content"
            //         //     ),
            //         // },
            //         success: (data) => {
            //             console.log("Location saved successfully.");
            //         },
            //         error: (xhr, status, error) => {
            //             console.error("Error saving location: ", error);
            //         },
            //     });
            // },
            // (error) => {
            //     console.error("Error getting user location: ", error);
            }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
});