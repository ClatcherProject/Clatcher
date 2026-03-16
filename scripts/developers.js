app.controller("tab1Ctrl", easteregg => {

    if(window.location.hash === "#1")
        document.getElementById("tab1").checked = true;
    else if(window.location.hash === "#2")
        document.getElementById("tab2").checked = true;

    easteregg.apriljoke();
    easteregg.halloween();
    easteregg.christmas();
    easteregg.firework();
});

app.controller("tab2Ctrl", () => {
    hljs.highlightAll();
});