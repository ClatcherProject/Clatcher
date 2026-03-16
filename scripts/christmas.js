var christmasStyles = document.createElement("link");
christmasStyles.rel = "stylesheet";
christmasStyles.href = "/styles/christmas.css";
document.querySelector("head").appendChild(christmasStyles);

var div = document.createElement("div");
var ul = document.createElement("ul");
var h3 = document.createElement("h3");

var colors = ["red", "yellow", "blue", "pink", "red", "green", "blue", "yellow", "red", "pink", "blue", "yellow", "red", "green", "blue", "yellow", "red", "pink", "green", "blue", "pink", "red", "green", "blue"];

ul.classList.add("line");

colors.forEach((item, idx) => {
    var li = document.createElement("li");
    li.classList.add(item);
    li.classList.add("li");
    li.style.cursor = "pointer";
    li.addEventListener("click", event => {
        li.remove();

        if(document.querySelector(".line").innerHTML === "") {
            document.querySelector(".headline").classList.replace("invisible", "visible");
        }
    });

    ul.appendChild(li);
});

div.classList.add("light");
div.appendChild(ul);

// document.querySelectorAll(".navbar").forEach(elem => {
    var h3 = document.createElement("h3");
    h3.classList.add("headline");
    h3.classList.add("invisible");
    h3.innerText = "Merry Christmas !";

    document.querySelector(".tabPage").prepend(h3);
    document.querySelector(".tabPage").prepend(div);
// });