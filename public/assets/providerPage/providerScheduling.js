$(document).ready(function () {
    var calendarEl = document.getElementById("calendar");

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
                },
            },
        },
        datesSet: function (view) {
            // Added event handler
            // Update title or span element here (optional)
            let currentDate = $(".fc-toolbar-title").text();
            $(".calendar-date").text(currentDate);
        },
        
    });
    calendar.render();

    // $(".add-new-shift-btn").click(function () {});

    $(".repeat-switch").on("click", function () {
        if ($(".repeat-switch").is(":checked")) {
            $(".checkboxes-section .form-check-input").prop("disabled", false);
            $(".repeat-end-selection").prop("disabled", false);
        } else {
            $(".checkboxes-section .form-check-input").prop("disabled", true);
            $(".repeat-end-selection").prop("disabled", true);
        }
    });

    $(".fc-customAddShift-button").click(function () {
        $(".physicianRegions").empty();
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

                var repeatEnd = new Date(event.shiftDate);
                if (event.is_repeat == 1) {
                    if (event.repeat_upto == 2) {
                        repeatEnd.setDate(repeatEnd.getDate() + 14);
                    } else if (event.repeat_upto == 3) {
                        repeatEnd.setDate(repeatEnd.getDate() + 21);
                    } else if (event.repeat_upto == 4) {
                        repeatEnd.setDate(repeatEnd.getDate() + 28);
                    }
                    repeatEnd.toISOString().split("T")[0];
                    eventData = {
                        title: event.title,
                        // start: startTime,
                        // end: endTime,
                        resourceId: event.resourceId,
                        daysOfWeek: event.week_days,
                        startTime: event.startTime,
                        endTime: event.endTime,
                        startRecur: event.shiftDate,
                        endRecur: repeatEnd,
                        textColor: "#000",
                        backgroundColor: "rgb(167, 204, 163)",
                    };
                    events.push(eventData);
                }

                eventData = {
                    title: event.title,
                    start: startTime,
                    end: endTime,
                    resourceId: event.resourceId,
                    textColor: "#000",
                    backgroundColor: "rgb(167, 204, 163)",
                    // Update the event
                    // calendar.getEventById(physicianId).update({
                    //     title: physician,
                    //     start: startDate,
                    //     end: endDate,
                    // });
                    // calendar.render();
                };
                events.push(eventData);
                return events;
            });
            calendar.addEventSource(events);
        },
    });
});
