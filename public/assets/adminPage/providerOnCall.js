$(document).ready(function () {
    $(".providerOnCallRegionFilter").on("change", function () {
        let regionId = $(this).val();

        $.ajax({
            url: "/filterProvidersByRegion/" + regionId,
            type: "GET",
            success: function (data) {
                $(".onDuty-provider-grid").empty();
                $(".offDuty-provider-grid").empty();

                if (data.onDutyPhysicians) {
                    data.onDutyPhysicians.forEach(function (onCallPhysician) {
                        $(".onDuty-provider-grid").append(
                            `<div class="provider">
                            <img src="${imageUrl}/${onCallPhysician.photo}" class="provider-profile-photo"
                            alt="provider profile photo" />
                            <span> 
                            Dr. ${onCallPhysician.first_name} ${onCallPhysician.last_name}
                            </span>
                            </div>`
                        );
                    });
                }
                console.log(data.offDutyPhysicians);
                if (data.offDutyPhysicians) {
                    data.offDutyPhysicians.forEach(function (offDutyProvider) {
                        $(".offDuty-provider-grid").append(
                            `<div class="provider">
                            <img src="${imageUrl}/${offDutyProvider.photo}" class="provider-profile-photo"
                                alt="provider profile photo">
                            <span>
                                Dr. ${offDutyProvider.first_name} ${offDutyProvider.last_name}
                            </span>
                        </div>`
                        );
                    });
                }
            },
            error: function (error) {
                console.error(error);
            },
        });
    });
});
