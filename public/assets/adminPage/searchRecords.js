
$(document).ready(function () {
    $('.export-data-to-excel').click(function () {
        $('#exportSearchForm').attr('action', "/search-records/export");
        $('#exportSearchForm').submit();

    })
})