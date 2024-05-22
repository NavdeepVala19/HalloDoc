
$(document).ready(function () {

    //* this code is for admin/new export
    $('#filterExportBtnNew').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient").val();
        var region_value = $(".listing-region").val() === "All Regions" ? "" : $(".listing-region").find('option:selected').text();
        var category_value = $(".filter-btn.active-filter").attr('data-category');
        

        $("input[name='status']").attr("value", 'new');
        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

        $("#filterExport").attr("action", "/admin-export");
        $('#filterExport').submit();

    })

 
    //* this code is for admin/pending export
    $('#filterExportBtnPending').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient").val();
        var region_value = $(".listing-region").val() === "All Regions" ? "" : $(".listing-region").val();
        var category_value = $(".filter-btn.active-filter").attr('data-category');

        $("input[name='status']").attr("value", "pending");
        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

        $("#filterExport").attr("action", "/admin-export");
        $('#filterExport').submit();

    })


    //* this code is for admin/active export
    $('#filterExportBtnActive').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient").val();
        var region_value = $(".listing-region").val() === "All Regions" ? "" : $(".listing-region").val();
        var category_value = $(".filter-btn.active-filter").attr('data-category');

        $("input[name='status']").attr("value", "active");
        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

         $("#filterExport").attr("action", "/admin-export");
        $('#filterExport').submit();

    })

    //* this code is for admin/conclude export
    $('#filterExportBtnConclude').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient").val();
        var region_value = $(".listing-region").val() === "All Regions" ? "" : $(".listing-region").val();
        var category_value = $(".filter-btn.active-filter").attr('data-category');

        $("input[name='status']").attr("value", "conclude");
        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

         $("#filterExport").attr("action", "/admin-export");
        $('#filterExport').submit();

    })

 
    //* this code is for admin/toclose export
    $('#filterExportBtnToClose').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient").val();
        var region_value = $(".listing-region").val() === "All Regions" ? "" : $(".listing-region").val();
        var category_value = $(".filter-btn.active-filter").attr('data-category');

        $("input[name='status']").attr("value", "toclose");
        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

         $("#filterExport").attr("action", "/admin-export");
        $('#filterExport').submit();

    })


    //* this code is for admin/unpaid export
    $('#filterExportBtnUnPaid').click(function (e) {
        e.preventDefault();

        var search_value = $(".search-patient").val();
        var region_value = $(".listing-region").val() === "All Regions" ? "" : $(".listing-region").val();
        var category_value = $(".filter-btn.active-filter").attr('data-category');

        $("input[name='status']").attr("value", "unpaid");
        $("input[name='filter_search']").attr("value", search_value);
        $("input[name='filter_region']").attr("value", region_value);
        $("input[name='filter_category']").attr("value", category_value);

         $("#filterExport").attr("action", "/admin-export");
        $('#filterExport').submit();

    })

})