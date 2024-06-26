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

        if (page === "›" || page === "Next »") {
            var pageNumberAttr = $(this).attr("href"); // it gives href attribute
            const regex = /\d+$/; // match one or more digits

            const matchRegex = pageNumberAttr.match(regex); // match with defined regex with value which gives href and it will output of type object

            if (matchRegex) {
                // Extracted digit is the first element of the match object

                const pageNumber = matchRegex[0]; // This will output "pageNumber (1,2,3,....)"
                var page = pageNumber;
            } else {
                console.log("there is no page number found");
            }
        } else if (page === "‹" || page === "« Previous") {
            var pageNumberAttr = $(this).attr("href"); // it gives href attribute
            const regex = /\d+$/; // match one or more digits

            const matchRegex = pageNumberAttr.match(regex); // match with defined regex with value which gives href and it will output of type object

            if (matchRegex) {
                // Extracted digit is the first element of the match object

                const pageNumber = matchRegex[0]; // This will output "pageNumber (1,2,3,....)"
                var page = pageNumber;
            } else {
                console.log("there is no page number found");
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
