$(document).ready(function () {
    let status;

    $(".nav-link").on("click", function () {
        let index = $(this).index();

        let tabNames = ["New", "Pending", "Active", "Conclude"];
        let tabName = tabNames[index];
        $("#selectedTab").text("(" + tabName + ")");

        status = index + 1;
        console.log(updateRoute);
    });

    $("#allLink").on("click", function (e) {
        e.preventDefault();
        let newRoute =  $(this).attr("href");
        newRoute = newRoute.replace(/status=[^&]*/, 'status=' + status);
        console.log(newRoute);
    });

    // Mobile Listing view
    $(".mobile-list").on("click", function () {
        // Target the next sibling .more-info element specifically
        $(this).next(".more-info").toggleClass("active");

        // Close any other open .more-info sections
        $(".more-info").not($(this).next(".more-info")).removeClass("active");
    });
});
