$(document).ready(function () {
    var calendarEl = document.getElementById("calendar");

    // Create new calendar view
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev next",
            center: "title",
            right: "customAddShift",
        },
        customButtons: {
            customAddShift: {
                text: "Add New Shift",
                click: function () {
                    $(".create-shift").show();
                    $(".overlay").show();

                    let todaysDate = new Date().toISOString().split("T")[0];
                    $(".shiftDate").val(todaysDate);

                    const hours = new Date()
                        .getHours()
                        .toString()
                        .padStart(2, "0");
                    const minutes = new Date()
                        .getMinutes()
                        .toString()
                        .padStart(2, "0");
                    const currentTime = `${hours}:${minutes}`;

                    $(".shiftStartTime").val(currentTime);
                },
            },
        },
        datesSet: function (view) {
            // Added event handler
            let currentDate = $(".fc-toolbar-title").text();
            $(".calendar-date").text(currentDate);
        },
        eventClick: function (info) {
            let shiftDate = info.event.startStr.slice(0, 10);
            let startTime = info.event.startStr.slice(11, 19);
            let endTime = info.event.endStr.slice(11, 19);

            $(".view-shift").show();
            $(".overlay").show();

            $(".region-view-shift").append(
                "<option value='" +
                    info.event.extendedProps.regionId +
                    "' selected >" +
                    info.event.extendedProps.regionName +
                    "</option>"
            );

            $(".shiftId").val(info.event.extendedProps.shiftId);
            $(".shiftDetailId").val(info.event.extendedProps.shiftDetailId);
            $(".shiftDate").val(shiftDate);
            $(".shiftStartTime").val(startTime);
            $(".shiftEndTime").val(endTime);
        },
    });
    calendar.render();

    // show and hide repeat section based on switch, checked or unchecked
    $(".repeat-switch").on("click", function () {
        if ($(".repeat-switch").is(":checked")) {
            $(".checkboxes-section .form-check-input").prop("disabled", false);
            $(".repeat-end-selection").prop("disabled", false);
            $(".repeat-section").show();
        } else {
            $(".checkboxes-section .form-check-input").prop("disabled", true);
            $(".repeat-end-selection").prop("disabled", true);
            $(".repeat-section").hide();
        }
    });

    // show add new shift pop-up
    $(".fc-customAddShift-button").click(function () {
        console.log("Button Clicked!!!");
        $(".physicianRegions option").remove();
        $.ajax({
            url: "/provider-information",
            type: "GET",
            success: function (data) {
                $("#providerId").val(data["physicianId"]);
                data["allRegions"].forEach((region) => {
                    $(".physicianRegions").append(
                        "<option value='" +
                            region.region_id +
                            "'>" +
                            region.regions.region_name +
                            "</option>"
                    );
                });
            },
            error: function (error) {
                console.error(error);
            },
        });
    });

    var events = [];
    var eventData;
    $.ajax({
        url: "/provider-shift",
        type: "GET",
        success: function (response) {
            response.map(function (event) {
                var shiftDate = event.shiftDate;
                var shiftStartTime = event.startTime;
                var shiftEndTime = event.endTime;

                var dateParts = shiftDate.split("-");

                var startTimeParts = shiftStartTime.split(":");
                var startTime = new Date(
                    dateParts[0],
                    dateParts[1] - 1,
                    dateParts[2],
                    startTimeParts[0],
                    startTimeParts[1]
                );

                var endTimeParts = shiftEndTime.split(":");
                var endTime = new Date(
                    dateParts[0],
                    dateParts[1] - 1,
                    dateParts[2],
                    endTimeParts[0],
                    endTimeParts[1]
                );

                eventData = {
                    title: event.title,
                    start: startTime,
                    end: endTime,
                    resourceId: event.resourceId,
                    textColor: "#000",
                    extendedProps: {
                        shiftId: event.shiftId,
                        shiftDetailId: event.shiftDetailId,
                        physicianId: event.physician_id,
                        physicianName: event.title,
                        regionId: event.region_id,
                        regionName: event.region_name,
                    },
                    backgroundColor:
                        event.status == "approved"
                            ? "rgb(167, 204, 163)"
                            : "rgb(240, 173, 212)",
                    className:
                        event.status == "approved"
                            ? "approved-shift-style"
                            : "pending-shift-style",
                };
                events.push(eventData);
                return events;
            });
            calendar.addEventSource(events);
        },
    });

    $(".edit-btn").click(function () {
        $(".shiftDateInput, .shiftStartTimeInput, .shiftEndTimeInput").attr(
            "disabled",
            false
        );
        $(".save-btn").show();
        $(".edit-btn").hide();
    });
    $(".view-shift-close").click(function () {
        $(".shiftDateInput, .shiftStartTimeInput, .shiftEndTimeInput").attr(
            "disabled",
            true
        );
        $(".save-btn").hide();
        $(".edit-btn").show();

        // $("#providerEditShiftForm").trigger("reset");
        $("#providerEditShiftForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    $(".providerAddShiftCancel").click(function () {
        $("#providerAddShiftForm").trigger("reset");
        $("#providerAddShiftForm").validate().resetForm();
        $(
            ".pop-up form .form-control, .pop-up form .form-select, .pop-up form .form-check-input"
        ).removeClass("is-valid");
        $(
            ".pop-up form .form-control, .pop-up form .form-select, .pop-up form .form-check-input"
        ).removeClass("is-invalid");
    });
});
