$(document).ready(function () {
    // Toggle Theme Implementation
    // Check if a theme preference is stored in local storage
    const themePref = localStorage.getItem("theme");

    // Apply the stored theme or default to light theme
    if (themePref) {
        $("html").addClass(themePref); // Add the theme preference class
    } else {
        $("html").addClass("light");
        localStorage.setItem("theme", "light");
    }

    // Toggle theme on button click
    $("#toggle-mode").click(function () {
        const currentTheme = $("html").attr("class"); // Get current class
        const newTheme = currentTheme === "light" ? "dark" : "light";

        // Update body class and local storage
        $("html").removeClass(currentTheme).addClass(newTheme);
        localStorage.setItem("theme", newTheme);
    });

    $("nav > a").click(function (e) {
        $("nav > a").removeClass("active-link");
        $(this).addClass("active-link");
    });

    $(".menu-icon").click(function (e) {
        e.preventDefault();
        let message = $(".welcome-msg").html();
        $(".mobile-message").remove();
        $(".navbar-section").prepend(
            $("<div class='mobile-message'>" + message + "</div>")
        );
        $(".navbar-section").toggleClass("mobile-nav");
        $(".blur-container").toggleClass("blur-active");
        $("html, body").toggleClass("stop-scrolling");
    });

    $("#telephone").intlTelInput({
        initialCountry: "in",
    });

    setTimeout(function () {
        $(".popup-message").fadeOut("slow");
    }, 2000);
});
