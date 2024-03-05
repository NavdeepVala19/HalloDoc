$(document).ready(function () {
    // $("#calendar").fullCalendar({});
    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        // headerToolbar: { end: "dayGridMonth,timeGridWeek" },
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev next",
            center: "title",
            right: "timeGridDay timeGridWeek dayGridMonth",
        },
    });
    calendar.render();
});
