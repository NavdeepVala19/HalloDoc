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
    var page = $(this).text();
    var selectedAccount = $("#accountType").val();
    fetchPaginatedUserAccessData(selectedAccount, page);
});

$("#accountType").on("change", function (event) {
    event.preventDefault();
    var selectedAccount = $(this).val();
    fetchPaginatedUserAccessData(selectedAccount, 1);
});
