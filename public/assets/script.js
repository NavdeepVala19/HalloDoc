$(document).ready(function () {
    $("nav > a").click(function (e) {
        // e.preventDefault();
        $("nav > a").removeClass("active-link");
        $(this).addClass("active-link");
    });

    $("#toggle-mode").click(function () {
        $("html").toggleClass("dark");
    });

    $(".menu-icon").click(function (e) {
        e.preventDefault();
        $(".navbar-section").toggleClass("mobile-nav");
    });

    $("#telephone").intlTelInput({
        initialCountry: "in",
    });

});
