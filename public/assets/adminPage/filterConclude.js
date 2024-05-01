
//* Filter Patient by Region in admin conclude listing
function fetchPaginatedResults(
    selectedId,
    activeStatus,
    page,
    search_value,
    category_value
) {
    var token = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: "/filter-conclude?page=" + page,
        type: "POST",
        dataType: "json",
        data: {
            regionId: selectedId,
            status: activeStatus,
            search_value: search_value,
            category_value: category_value,
            _token: token,
        },
        success: function (response) {
            $(".AdminConcludeListingPage").html(response.html); // Update results area
            $(".adminNewListingPages").hide();
        },
        error: function (error) {
            console.error(error);
        },
    });
}

$(document).on('click', '.pagination .page-link', function (event) {
    event.preventDefault();

      var page;
      page = $(this).text();

      if (page === "›") {
          // Get the <li> element with the class "active"
          var activeListItem = $(".pagination .page-item.active");

          // Get the next sibling of the active <li> element
          var nextSibling = activeListItem.next();

          // Check if the next sibling exists and does not have the "active" class
          if (nextSibling.length && !nextSibling.hasClass("active")) {
              // Get the value of the next sibling
              var page = nextSibling.first(".page-link").text();
          } else {
              console.log(
                  "There is no next sibling without the 'active' class."
              );
          }
      } else if (page === "‹") {
          // Get the <li> element with the class "active"
          var activeListItem = $(".pagination .page-item.active");

          // Get the next sibling of the active <li> element
          var prevSibling = activeListItem.prev();

          // Check if the next sibling exists and does not have the "active" class
          if (prevSibling.length && !prevSibling.hasClass("active")) {
              // Get the value of the next sibling
              var page = prevSibling.first(".page-link").text();
          } else {
              console.log(
                  "There is no previous sibling without the 'active' class."
              );
          }
      }

    var tab = $(".nav-link.active").attr("id");
    var words = tab.split("-");
    var selectedId = $('.listing-region').val(); 
    var activeStatus = words[1];
        var search_value = $(".search-patient").val();
        var category_value = $(".filter-btn.active-filter").attr(
            "data-category"
        );
    fetchPaginatedResults(
        selectedId,
        activeStatus,
        page,
        search_value,
        category_value
    );
});


$('.listing-region').on('change', function (event) {
    event.preventDefault();

    var tab = $(".nav-link.active").attr("id");
    var words = tab.split("-");
    var selectedId = $(this).val();
    var activeStatus = words[1];
        var search_value = $(".search-patient").val();
        var category_value = $(".filter-btn.active-filter").attr(
            "data-category"
        );
    fetchPaginatedResults(
        selectedId,
        activeStatus,
        1,
        search_value,
        category_value
    );
});
