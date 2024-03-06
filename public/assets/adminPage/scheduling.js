$(document).ready(function () {
    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: "prev next",
            center: "title",
            right: "resourceTimelineDay dayGridWeek dayGridMonth",
        },
        initialView: "resourceTimelineDay",
        aspectRatio: 1.5,
        resourceAreaColumns: [{ field: "physician", headerContent: "Staff" }],
        resources: {
            url: "/provider-data",
            // dataType: "json",
        },

        // views: {
        //     resourceTimelineWeek: {
        //         type: "resourceTimelineWeek",
        //         slotDuration: {
        //             days: 1,
        //         },
        //         slotLabelInterval: {
        //             days: 1,
        //         },
        //         slotLabelFormat: [
        //             {
        //                 weekday: "long",
        //             }, // lower level of text
        //             {
        //                 month: "long",
        //                 day: "numeric",
        //             }, // lower level of text
        //         ],
        //     },
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
                var link = `<img src=""` + arg.resource.extendedProps.physician;
            } else {
                var link = "Data";
            }
            return {
                html: link,
            };
        },
    });
    calendar.render();
    let date = $(".fc-toolbar-title").text();
    $(".date-title").html(date);
});

// dragScroll: true,
// eventColor: '#378006'
