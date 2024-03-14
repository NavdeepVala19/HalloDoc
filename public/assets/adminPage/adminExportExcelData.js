
$(document).ready(function () {

    $('#filterExportBtnNew').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient-new").val();
        var region_value = $(".listing-region-new").val() === "All Regions" ? "" : $(".listing-region-new").val();
        var category_value = $(".filter-btn").val();

        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

        $('#filterExport').attr('action', "/admin/new/exportNew");
        $('#filterExport').submit();

    })



    $('#filterExportBtn').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient-pending").val();
        var region_value = $(".listing-region-pending").val() === "All Regions" ? "" : $(".listing-region-pending").val();
        var category_value = $(".filter-btn").val();

        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

        $('#filterExport').attr('action', "/admin/new/exportPending");
        $('#filterExport').submit();

    })



    $('#filterExportBtn').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient-active").val();
        var region_value = $(".listing-region-active").val() === "All Regions" ? "" : $(".listing-region-active").val();
        var category_value = $(".filter-btn").val();

        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

        $('#filterExport').attr('action', "/admin/new/exportActive");
        $('#filterExport').submit();

    })


    $('#filterExportBtn').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient-conclude").val();
        var region_value = $(".listing-region-conclude").val() === "All Regions" ? "" : $(".listing-region-conclude").val();
        var category_value = $(".filter-btn").val();

        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

        $('#filterExport').attr('action', "/admin/new/exportConclude");
        $('#filterExport').submit();

    })



    $('#filterExportBtn').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient-toclose").val();
        var region_value = $(".listing-region-toclose").val() === "All Regions" ? "" : $(".listing-region-toclose").val();
        var category_value = $(".filter-btn").val();

        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

        $('#filterExport').attr('action', "/admin/new/exportToClose");
        $('#filterExport').submit();

    })



    $('#filterExportBtn').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient-unpaid").val();
        var region_value = $(".listing-region-unpaid").val() === "All Regions" ? "" : $(".listing-region-unpaid").val();
        var category_value = $(".filter-btn").val();

        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

        $('#filterExport').attr('action', "/admin/new/exportUnPaid");
        $('#filterExport').submit();

    })

})