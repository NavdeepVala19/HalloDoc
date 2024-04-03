$(document).ready(function () {
    $("#adminSendLinkButton").click(function () {
        $("#adminSendLinkForm").submit(function (e) {
            e.preventDefault();
        });
    });
});
