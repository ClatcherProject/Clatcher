const layers = [
    "#user-info",
    "#comment-layer",
    "#user-layer",
    "#private-comment-layer",
    "#user-storage",
    "#games",
    "#user-settings",
    "#webradio",
    "#mailconfirm"
];

const dragElement = (elem) => {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if(document.getElementById(elem.id + "-header")) {
        document.getElementById(elem.id + "-header").onmousedown = dragMouseDown;
    }
    else {
        elem.onmousedown = dragMouseDown;
    }
    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        pos3 = e.clientX;
        pos4 = e.clientY;
        elem.style.opacity = "0.5";
        elem.style.zIndex = "100";
        document.onmouseup = closeDragElement;
        document.onmousemove = elementDrag;
    }
    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        var elemTop = elem.offsetTop - pos2;
        if(elemTop > 0)
            elem.style.top = elemTop + "px";
        
        var elemLeft = elem.offsetLeft - pos1;
        if(elemLeft > -1)
            elem.style.left = elemLeft + "px";
    }
    function closeDragElement() {
        elem.style.opacity = "1.0";
        elem.style.zIndex = "0";
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

document.querySelectorAll(layers).forEach(elem => {
    dragElement(elem); // make all div-layers movable
});