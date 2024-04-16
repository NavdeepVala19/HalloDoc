$(document).ready(function () {
    $.ajax({
        url: "/providers-map-Locations",
        type: "get",
        success: function (response) {
            response.locations.forEach(function (location) {
                getAddressFromCoordinate(
                    location.latitude,
                    location.longitude,
                    function (address) {
                        // Construct the iframe URL with the address
                        var iframeUrl =
                            "https://www.google.com/maps?q=" +
                            encodeURIComponent(address) +
                            "&output=embed";
                        // Create the iframe element
                        var iframe = $(
                            '<iframe width="600" height="450" frameborder="0" style="border:0" src="' +
                                iframeUrl +
                                '"></iframe>'
                        );
                        // Append the iframe to the container
                        $("#map-containe").append(iframe);
                    }
                );
            });
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });

    function getAddressFromCoordinates(latitude, longitude, callback) {
        var geocoder = new google.maps.Geocoder();
        var latlng = { lat: parseFloat(latitude), lng: parseFloat(longitude) };

        geocoder.geocode({ location: latlng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var formattedAddress = results[0].formatted_address;
                    // console.log("Address:", formattedAddress);

                    // Pass the address to the callback function
                    callback(formattedAddress);
                } else {
                    console.error("No results found");
                }
            } else {
                console.error("Geocoder failed due to: " + status);
            }
        });
    }
});
