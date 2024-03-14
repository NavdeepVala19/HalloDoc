$(document).ready(function () {
    $(".new-shift-btn").click(function () {
        $(".create-shift").show();
        $(".overlay").show();
    });

    $(".repeat-switch").on("click", function () {
        if ($(".repeat-switch").is(":checked")) {
            $(".checkboxes-section .form-check-input").prop("disabled", false);
            $('.repeat-end-selection').prop('disabled', false);
        } else {
            $(".checkboxes-section .form-check-input").prop("disabled", true);
            $('.repeat-end-selection').prop('disabled', true);
        }
    });
    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: "prev next",
            center: "title",
            right: "resourceTimelineDay resourceTimelineWeek dayGridMonth",
        },
        initialView: "resourceTimelineDay",
        selectable: true,
        selectMirror: true,
        // unselectAuto: true,
        aspectRatio: 1.5,
        resourceAreaColumns: [{ field: "physician", headerContent: "Staff" }],
        resourceAreaWidth: "20%",
        resources: {
            url: "/provider-data",
            // dataType: "json",
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
        select: function (selectInfo) {
            // console.log(selectInfo);
            $(".create-shift").show();
            $(".overlay").show();
        },
        // dateClick: function (info) {
        //     // alert(info);
        // },

        // resourceLabelContent: function (arg) {
        //     console.log(arg);
        //     // arg.resource.extendedProps.
        //     if (arg.resource.id > 0) {
        //         var editUserLink = userEditLink.replace(
        //             "userId",
        //             arg.resource.id
        //         );
        //         var link =
        //             '<a href="javascript:void(0)" onclick="addVacation(' +
        //             arg.resource.id +
        //             ')" class="add-vacation" style ="padding-right: 10px;display: inline-flex;" title="Add Vacation"><i class="fal fa-plus-square"></i></a><a href=' +
        //             editUserLink +
        //             ">" +
        //             arg.resource.title +
        //             "</a>";
        //     } else {
        //         var link = arg.resource.title;
        //     }
        //     return {
        //         html: link,
        //     };
        // },

        eventContent: function (arg) {
            console.log(arg.resource.extendedProps);
            // arg.resource.extendedProps.
            if (arg.resource.id > 0) {
                var link =
                    `<img src="{{URL::asset('')}}"` +
                    arg.resource.extendedProps.physician;
            } else {
                var link = "Data";
            }
            return {
                html: link,
            };
        },

        dayHeaderFormat: {
            day: "numeric",
            weekday: "short",
            omitComma: true,
        },
    });
    calendar.render();
    let date = $(".fc-toolbar-title").text();
    $(".date-title").html(date);
});

// eventColor: '#378006'
