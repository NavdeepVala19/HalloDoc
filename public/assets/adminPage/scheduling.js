$(document).ready(function () {
    $(".new-shift-btn").click(function () {
        $(".create-shift").show();
        $(".overlay").show();
    });
    

    $(".repeat-switch").on("click", function () {
        if ($(".repeat-switch").is(":checked")) {
            $(".checkboxes-section .form-check-input").prop("disabled", false);
            $(".repeat-end-selection").prop("disabled", false);
        } else {
            $(".checkboxes-section .form-check-input").prop("disabled", true);
            $(".repeat-end-selection").prop("disabled", true);
        }
    });

    $(".physicianRegions").on("change", function () {
        $regions = $(this).val();
        $(".physicianSelection").empty();
        $.ajax({
            url: "/physician/" + $regions,
            type: "GET",
            success: function (data) {
                data.forEach(function (physician) {
                    $(".physicianSelection").append(
                        '<option value="' +
                            physician.id +
                            '">' +
                            physician.first_name +
                            " " +
                            physician.last_name +
                            "</option>"
                    );
                });
            },
            error: function (error) {
                console.error(error);
            },
        });
    });

    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev next",
            center: "title",
            right: "resourceTimelineDay resourceTimelineWeek dayGridMonth",
        },
        selectable: true,
        aspectRatio: 1.5,
        resourceAreaWidth: "20%",
        dayHeaderFormat: {
            day: "numeric",
            weekday: "short",
            omitComma: true,
        },
        datesSet: function (view) {
            // Added event handler
            let currentDate = $(".fc-toolbar-title").text();
            $(".date-title").text(currentDate);
        },
        resourceAreaColumns: [{ field: "physician", headerContent: "Staff" }],
        resources: {
            url: "/provider-data",
        },
        views: {
            resourceTimelineWeek: {
                slotDuration: { days: 1 },
                slotLabelInterval: { days: 1 },
                slotLabelFormat: [
                    {
                        weekday: "short",
                        day: "numeric",
                    },
                ],
            },
        },
        resourceLabelContent: function (arg) {
            if (arg.resource.id > 0) {
                var link = `<img class="resource-img" src="${window.location.origin}/storage/${arg.resource.extendedProps.photo}" alt="${arg.resource.extendedProps.physician}"/> <span>${arg.resource.extendedProps.physician}</span>`;
            } else {
                var link = "Data";
            }
            return {
                html: link,
            };
        },
        select: function (selectInfo) {
            let shiftDate = selectInfo.startStr.slice(0, 10);
            let startTime = selectInfo.startStr.slice(11, 19);
            let endTime = selectInfo.endStr.slice(11, 19);

            let providerId = selectInfo.resource.id;
            let providerName = selectInfo.resource.extendedProps.physician;

            $(".create-shift").show();
            $(".overlay").show();

            $(".shiftDate").val(shiftDate);
            $(".shiftStartTime").val(startTime);
            $(".shiftEndTime").val(endTime);
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

            $(".physician-view-shift").append(
                "<option value='" +
                    info.event.extendedProps.physicianId +
                    "' selected>" +
                    info.event.extendedProps.physicianName +
                    "</option>"
            );

            $(".shiftId").val(info.event.extendedProps.shiftId);
            $(".shiftDate").val(shiftDate);
            $(".shiftStartTime").val(startTime);
            $(".shiftEndTime").val(endTime);
        },
    });
    calendar.render();

    var events = [];
    var eventData;
    $.ajax({
        url: "/events-data",
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
                        resourceId: event.resourceId,
                        daysOfWeek: event.week_days,
                        startTime: event.startTime,
                        endTime: event.endTime,
                        startRecur: event.shiftDate,
                        endRecur: repeatEnd,
                        textColor: "#000",
                        extendedProps: {
                            shiftId: event.shiftId,
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
                }

                eventData = {
                    title: event.title,
                    start: startTime,
                    end: endTime,
                    resourceId: event.resourceId,
                    textColor: "#000",
                    extendedProps: {
                        shiftId: event.shiftId,
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
    });

    
});
