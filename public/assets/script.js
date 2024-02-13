$(document).ready(function () {
    $("nav > a").click(function (e) {
        e.preventDefault();
        // console.log("Clicked");
        $("nav > a").removeClass("active-link");
        $(this).addClass("active-link");
    });

    // $(".toggle-mode").click(function (e) {
    //     e.preventDefault();
    //     console.log("button Clicked");
    //     document.documentElement.classList.toggle("dark");
    // });

    $(".menu-icon").click(function (e) {
        e.preventDefault();
        console.log("btn-clicked");
        $(".navbar-section").toggleClass("mobile-nav");
    });

    $("#telephone").intlTelInput({
        initialCountry: "in",
    });
});


