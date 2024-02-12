$(document).ready(function () {

    // $('#nav-tab a').on('click', function (e) {
    //     e.preventDefault();
    //     // Get the selected tab's href
    //     var selectedTab = $(this).attr('href');
    //     // Update the URL without page reload
    //     history.replaceState({}, '', selectedTab);
    //     // Show the selected tab
    //     $(this).tab('show');
    // });

    // let tabName = "new";
    // dynamically change the status type()
    // $(".nav-link").on("click", function (e) {
    //     let index = $(this).index();
    //     let tabNames = ["new", "pending", "active", "conclude"];
    //     tabName = tabNames[index];
    //     $("#selectedTab").text("(" + tabName + ")");
    //     console.log(tabName);
    // });

    // for filtering list based on the status and request-type (button functionality - all,patient,family,business,concierge)
    // $(".filter-btn").on("click", function (e) {
    //     // e.preventDefault();
    //     // let newRoute = $(this).attr("href").replace(/new*/, tabName);
    //     let newRoute = $(this).attr("href");
    //     // $(this).attr("href", newRoute);
    //     console.log(newRoute);
    //     history.pushState({}, '', newRoute);
    // });

    // Mobile Listing view
    $(".mobile-list").on("click", function () {
        // Target the next sibling .more-info element specifically
        $(this).next(".more-info").toggleClass("active");

        // Close any other open .more-info sections
        $(".more-info").not($(this).next(".more-info")).removeClass("active");
    });
});
