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
        initialView: "resourceTimelineDay",
        headerToolbar: {
            left: "prev next",
            center: "title",
            right: "resourceTimelineDay resourceTimelineWeek dayGridMonth",
        },
        selectable: true,
        // selectMirror: true,
        aspectRatio: 1.5,
        resourceAreaWidth: "20%",
        dayHeaderFormat: {
            day: "numeric",
            weekday: "short",
            omitComma: true,
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
        eventContent: function (arg) {
            console.log(arg);
            if (arg.resource.id > 0) {
                var link =
                    `<img src="{{URL::asset('')}}"` +
                    arg.resource.extendedProps.physician +
                    "Test";
            } else {
                var link = "Data";
            }
            return {
                html: link,
            };
        },
        select: function (selectInfo) {
            let providerId = selectInfo.resource.id;
            let providerName = selectInfo.resource.extendedProps.physician;
            $(".create-shift").show();
            $(".overlay").show();
        },
        // eventDataTransform: function (eventData) {
        //     // This hook allows you to receive arbitrary event data from a JSON feed or any other Event Source and transform it into the type of data FullCalendar accepts
        // },
    });
    calendar.render();

    $(".save-shift-btn").click(function () {
        var region = $(".region").val();
        var physicianId = $(".physicianSelection").val();
        var shiftDate = $(".shiftDate").val();
        var shiftStartTime = $(".shiftStartTime").val();
        var shiftEndTime = $(".shiftEndTime").val();

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
        calendar.addEvent(
            //     {
            //     title: "Test",
            //     start: startTime,
            //     end: endTime,
            //     resourceId: physicianId,
            // }
            {
                title: "Dummy Event",
                start: new Date(), // today's date
                end: new Date(new Date().getTime() + 2 * 60 * 60 * 1000), // 2 hours from now
                resourceId: 1,
            }
        );
    });

    let date = $(".fc-toolbar-title").text();
    $(".date-title").html(date);
});

// eventColor: '#378006'
