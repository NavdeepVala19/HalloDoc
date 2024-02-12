$(document).ready(function () {
    let tabName = "new";
    // dynamically change the status type()
    $(".nav-link").on("click", function (e) {
        // e.preventDefault();
        let index = $(this).index();

        let tabNames = ["new", "pending", "active", "conclude"];
        tabName = tabNames[index];
        $("#selectedTab").text("(" + tabName + ")");
        console.log(tabName);

    });

    // for filtering list based on the status and request-type (button functionality - all,patient,family,business,concierge)
    $(".status-link").on("click", function (e) {
        // e.preventDefault();
        let newRoute = $(this).attr("href").replace(/new*/, tabName);
        $(this).attr("href", newRoute);
        console.log($(this).attr("href"));
    });

    // Mobile Listing view
    $(".mobile-list").on("click", function () {
        // Target the next sibling .more-info element specifically
        $(this).next(".more-info").toggleClass("active");

        // Close any other open .more-info sections
        $(".more-info").not($(this).next(".more-info")).removeClass("active");
    });
});
