$(document).ready(function () {
    $('#blockListTable').on('change', '.form-check-input', function (e) {
        var token = $('meta[name="csrf-token"]').attr('content')
        var checkbox = $(this);
        var blockId = checkbox.attr('id').split('_')[1];
        var isActive = checkbox.prop('checked') ? 1 : 0; // Ternary operator to set isActive


        $.ajax({
            url: "/block-history/update",
            type: 'POST',
            data: {
                blockId: blockId,
                is_active: isActive,
                "_token": token
            },
            success: function (response) {
            },
            error: function (error) {
                console.error('Error updating block:', error);
            }
        });
    });
});

