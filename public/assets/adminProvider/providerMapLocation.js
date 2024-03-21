
$(document).ready(function () {

    function updateMap(providers) {

        //var addresses retrieves JSON representation of the providers from adminProviderController ,it is an array contains address details

        console.log(json(providers));
        var addresses = @json(providers);


        var mapUrl = "https://www.google.com/maps?q=";


        // This forEach takes callback function as an argument and 
        // callback function takes 2 parameters 1st is provider(the current element of array) and 2nd is index(the index of current element)

        addresses.forEach(function (provider, index) {
            if (index !== 0) {
                mapUrl += "+";
            }

            mapUrl += encodeURIComponent(provider.address1 + ", " + provider.address2 + ", " + provider.city + ", " + provider.zipcode);
        });

        document.getElementById('map-iframe').src = mapUrl + "&output=embed";
    }

    // Call the function to update the map when the page loads
    updateMap();

})
