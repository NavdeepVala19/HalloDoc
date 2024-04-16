var iti;

$(document).ready(function () {
    // When Window loading is completed, hide the loader
    $(window).on("load", function () {
        $("#loading-icon").hide();
    });

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

    setTimeout(function () {
        $(".popup-message").fadeOut("slow");
    }, 2000);

    // No space are allowed directly when input field is empty
    $(
        'textarea, input[type="text"], input[type="email"], input[type="password"], input[type="tel"], input[type="number"], input[type="date"]'
    ).on("keypress", function (event) {
        // Check if space key is pressed and the input field is empty
        if (event.which === 32 && $(this).val().trim() === "") {
            event.preventDefault(); // Prevent space from being inserted
        } else {
            // Allow other key presses (including backspace, delete, etc.)
            return true;
        }
    });

    let telephone = document.querySelector("#telephone");
    if (telephone) {
        iti = window.intlTelInput(telephone, {
            initialCountry: "in",
            strictMode: true,
            utilsScript:
                "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/21.1.3/js/utils.min.js",
        });
    }
});
