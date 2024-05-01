//  * code for export data in excel in search records page
$(document).ready(function () {
    $(".export-data-to-excel").click(function () {
        $("#exportSearchForm").attr("action", "/search-records/export");
        $("#exportSearchForm").submit();

        $("#exportSearchForm").attr("action", "/search-records/search");
    });
});
