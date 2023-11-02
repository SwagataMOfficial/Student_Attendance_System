const collapse_btn = document.getElementById("collapse-btn");

collapse_btn.addEventListener("click", () => {
	document.querySelector("#collapse-btn .material-symbols-outlined")
		.classList.toggle("arrow-rotate");
	document.querySelector(".container .left-nav").classList.toggle("nav-active");
	document.querySelector(".container .right-main").classList.toggle("main-stretch");
	document.querySelector("#collapse-btn .collapse-name").classList.toggle("btn-collapse");
	document.querySelectorAll(".links p").forEach((e) => {
		e.classList.toggle("collapsed-sm");
	});
	document.querySelectorAll(".contents .links").forEach((e) => {
		e.classList.toggle("headings-no-link");
	});
	document.querySelectorAll(".link-heading").forEach((e) => {
		e.classList.toggle("link-paras");
	});
});
