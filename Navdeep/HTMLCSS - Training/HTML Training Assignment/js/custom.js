let menu_btn = document.getElementById("menu-btn");
let menu_btn_sm = document.querySelector(".menu-btn-sm");

let aside = document.getElementById("nav");
let section = document.querySelector("section");

menu_btn.addEventListener("click", () => {
  aside.classList.toggle("close-nav");
});

menu_btn_sm.addEventListener("click", () => {
  section.classList.toggle("menu-bar-sm");
});
