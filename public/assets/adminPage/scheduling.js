$(document).ready(function () {
    $(".new-shift-btn").click(function () {
        $(".create-shift").show();
        $(".overlay").show();

        let todaysDate = new Date().toISOString().split("T")[0];
        $(".shiftDate").val(todaysDate);

        const hours = new Date().getHours().toString().padStart(2, "0");
        const minutes = new Date().getMinutes().toString().padStart(2, "0");
        const currentTime = `${hours}:${minutes}`;

        $(".shiftStartTime").val(currentTime);
    });

    // show and hide the repeat selection based on switch (checked or unchecked)
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

    // Fetch Physician data to display in pop-ups
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

    // Create a new calendar view
    var calendar = new FullCalendar.Calendar(calendarEl, {
        // It's initial view should be MonthView
        initialView: "dayGridMonth",
        // It should have previous and next button for navigation, and should contain three view options(day, week, month)
        headerToolbar: {
            left: "prev next",
            center: "title",
            right: "resourceTimelineDay resourceTimelineWeek dayGridMonth",
        },
        // Events are selectable
        selectable: true,
        // For showing information of providers
        resourceAreaWidth: "20%",
        // Format of the time to be displayed as title
        dayHeaderFormat: {
            // day: "numeric",
            weekday: "short",
            // omitComma: true,
        },
        // want to show title in date-title rather than on calendar
        datesSet: function (view) {
            // Added event handler
            let currentDate = $(".fc-toolbar-title").text();
            $(".date-title").text(currentDate);
        },
        // To display physician data, create a new resourceArea column
        resourceAreaColumns: [{ field: "physician", headerContent: "Staff" }],
        // resources data is fetched from /provider-data route
        resources: {
            url: "/provider-data",
        },
        // For Week view in calendar changes in format
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
        // Displaying data fetched, in the resource label content
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
        // when selecting range in Day view a pop-up opens with the specified details
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
        // clicking on an event opens (viewShift/editShift) popup
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
            $(".shiftDetailId").val(info.event.extendedProps.shiftDetailId);
            $(".shiftDate").val(shiftDate);
            $(".shiftStartTime").val(startTime);
            $(".shiftEndTime").val(endTime);
        },
    });
    // Render calendar as per the above mentioned rules
    calendar.render();

    // ----------------------------- NEW CODE (Every shift is individually creating, including recurring shifts) -------------------------------------
    // An empty events array, which will store all the events that need to be rendered in calendar
    var events = [];
    var eventData;
    // An ajax call as soon as page is loaded on /events-data route
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

    // Filter shifts based on region selected
    $(".region-filter").on("change", function () {
        let regionId = $(this).val();
        calendar.removeAllEvents();
        events.length = 0;
        $.ajax({
            url: "/scheduling/region/" + regionId,
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
    });

    // when edit button is clicked on view-shift pop-up, enable input fields
    $(".edit-btn").click(function () {
        $(".shiftDateInput, .shiftStartTimeInput, .shiftEndTimeInput").attr(
            "disabled",
            false
        );
        $(".save-btn").show();
        $(".edit-btn").hide();
    });

    // reset form when pop-up is closed and disable all fields
    $(".view-shift-close").click(function () {
        $(".shiftDateInput, .shiftStartTimeInput, .shiftEndTimeInput").attr(
            "disabled",
            true
        );
        $(".save-btn").hide();
        $(".edit-btn").show();

        $("#adminEditShiftForm").trigger("reset");
        $("#adminEditShiftForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    // reset form when pop-up is closed
    $(".addShiftCancel").click(function () {
        $(".pop-up .physicianSelection")
            .empty()
            .append("<option selected disabled>Physicians</option>");

        $("#adminAddShiftForm").trigger("reset");
        $("#adminAddShiftForm").validate().resetForm();
        $(
            ".pop-up form .form-control, .pop-up form .form-select, .pop-up form .form-check-input"
        ).removeClass("is-valid");
        $(
            ".pop-up form .form-control, .pop-up form .form-select, .pop-up form .form-check-input"
        ).removeClass("is-invalid");
    });
});
