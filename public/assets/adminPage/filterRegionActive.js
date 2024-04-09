
// Filter Patient by Region in active
function fetchPaginatedResults(selectedId,activeStatus, page) {
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/filter-active?page=' + page,
        type: 'POST',
        dataType: 'json',
        data: {
            regionId: selectedId,
            status: activeStatus,
            _token: token,
        },
        success: function (response) {
            $('.table-responsive').html(response.html); // Update results area
            $('.adminNewListingPages').hide();
        },
        error: function (error) {
            console.error(error);
        },
    })
}

$(document).on('click', '.pagination .page-link', function (event) {
    event.preventDefault();

    $(".page-item").removeClass('active');
    $(this).closest('.page-item').addClass('active');
    var page = $(this).text();

    var tab = $(".nav-link.active").attr("id");
    var words = tab.split("-");
    var selectedId = $('.listing-region').val(); 
    var activeStatus = words[1];
    fetchPaginatedResults(selectedId, activeStatus, page);
});


$('.listing-region').on('change', function (event) {
    event.preventDefault();

    var tab = $(".nav-link.active").attr("id");
    var words = tab.split("-");
    var selectedId = $(this).val();
    var activeStatus = words[1];
    fetchPaginatedResults(selectedId,activeStatus, 1);
});
