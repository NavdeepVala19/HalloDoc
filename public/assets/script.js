$(document).ready(function () {
    $("nav > a").click(function (e) {
        e.preventDefault();
        // console.log("Clicked");
        $("nav > a").removeClass("active-link");
        $(this).addClass("active-link");
    });

    $(".toggle-mode").click(function (e) {
        e.preventDefault();
        // console.log("button Clicked");
        document.documentElement.classList.toggle("dark");
    });

    // $(".toggle-mode").onchange = (e) => {
    //     if (modeBtn === true) {
    //       document.documentElement.classList.remove("light")
    //       document.documentElement.classList.add("dark")
    //       window.localStorage.setItem('mode', 'dark');
    //     } else {
    //       document.documentElement.classList.remove("dark")
    //       document.documentElement.classList.add("light")
    //       window.localStorage.setItem('mode', 'light');
    //     }
    //   }
});
