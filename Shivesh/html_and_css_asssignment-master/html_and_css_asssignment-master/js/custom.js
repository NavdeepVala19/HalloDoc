let menu_btn = document.querySelector(".menu-img");
let aside_section = document.getElementById("myDIV");


menu_btn.addEventListener("click" ,() => {
    aside_section.classList.toggle("close-aside")
    console.log("button click")
});
