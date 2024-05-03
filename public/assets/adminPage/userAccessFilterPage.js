// ** fetch data using ajax and paginate through ajax 

function fetchPaginatedUserAccessData(selectedAccount, page) {
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/user-access/filter?page=" + page,
        type: "POST",
        data: {
            selectedAccount: selectedAccount,
            _token: token,
        },
        success: function (data) {
            $("#user-access-data").html(data.html); // Update results area
        },
    });
}

$(document).on("click", ".pagination .page-link", function (event) {
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
              console.log(page);
          } else {
              console.log(
                  "There is no next sibling without the 'active' class."
              );
          }
      }
    var selectedAccount = $("#accountType").val();
    fetchPaginatedUserAccessData(selectedAccount, page);
});

$("#accountType").on("change", function (event) {
    event.preventDefault();
    var selectedAccount = $(this).val();
    fetchPaginatedUserAccessData(selectedAccount, 1);
});



// * showing create admin/provider account button
$(document).ready(function () {
    $("#accountType").on("click", function () {
        if ($("#accountType").val() == "admin") {
            $("#createAdmin").show();
            $("#createPhysician").hide();
        } else if ($("#accountType").val() == "physician") {
            $("#createPhysician").show();
            $("#createAdmin").hide();
        } else if ($("#accountType").val() == "all") {
            $("#createAdmin").hide();
            $("#createPhysician").hide();
        }
    });

    $("#accountTypeMobile").on("click", function () {
        if ($("#accountTypeMobile").val() == "admin") {
            $("#createAdmin").show();
            $("#createPhysician").hide();
        } else if ($("#accountTypeMobile").val() == "physician") {
            $("#createPhysician").show();
            $("#createAdmin").hide();
        } else if ($("#accountTypeMobile").val() == "all") {
            $("#createAdmin").hide();
            $("#createPhysician").hide();
        }
    });
});




// ** fetch data using ajax and paginate through ajax  in mobile view

function fetchPaginatedUserAccessMobileData(selectedAccount, page) {
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/user-access-mobile-filter?page=" + page,
        type: "POST",
        data: {
            selectedAccount: selectedAccount,
            _token: token,
        },
        success: function (data) {
            $(".mobile-listing").html(data.html); // Update results area

            $(".main-section").click(function () {
                // Target the next sibling .more-info element specifically
                $(this).next(".details").toggleClass("active");

                $(".details")
                    .not($(this).next(".details"))
                    .removeClass("active");
            });
        },
    });
}

$(document).on("click", ".pagination .page-link", function (event) {
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
            console.log("There is no next sibling without the 'active' class.");
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
            console.log("There is no next sibling without the 'active' class.");
        }
    }

     if (page === "Next »") {
         var pageValue = $(this).attr("href");

         // Use regular expression to match the digit at the end of the "pageValue"
         const regex = /\d+$/; // match one or more digits

         const match = pageValue.match(regex);

         if (match) {
             // Extracted digit is the first element of the match array
             const pageNumber = match[0];
             var page = pageNumber;

             // This will output "pageNumber (1,2,3,....)"
             // console.log(pageNumber);
         } else {
             console.log("No page number found in the string");
         }
     } else if (page === "« Previous") {
         var pageValue = $(this).attr("href");

         // Use regular expression to match the digit at the end of the "pageValue"
         const regex = /\d+$/; // match one or more digits

         const match = pageValue.match(regex);

         if (match) {
             // Extracted digit is the first element of the match array
             const pageNumber = match[0];
             var page = pageNumber;

             // This will output "pageNumber (1,2,3,....)"
             //   console.log(pageNumber);
         } else {
             console.log("No page number found in the string");
         }
     }

    var selectedAccount = $("#accountTypeMobile").val();
    fetchPaginatedUserAccessMobileData(selectedAccount, page);
});

$("#accountTypeMobile").on("change", function (event) {
    event.preventDefault();
    var selectedAccount = $(this).val();
    fetchPaginatedUserAccessMobileData(selectedAccount, 1);
});
