// adding fontawesome-kit
const fontawesome = document.createElement("script");
fontawesome.src = "/fontawesome-free-7.2.0-web/js/all.min.js";
document.head.appendChild(fontawesome);

document.addEventListener("contextmenu", event => {
    event.preventDefault();
}, true);

document.querySelectorAll(".accordion-title").forEach(elem => {
    elem.addEventListener("click", event => {
        var body = event.target.nextElementSibling;
        if(body.classList.contains("close-accordion"))
            body.classList.replace("close-accordion", "open-accordion");
        else
            body.classList.replace("open-accordion", "close-accordion");
    });
});

const app = angular.module("tcapp", ["ngSanitize"]);

/**
 * CHANGE CONSTANTS HERE
 */
app.service("constants", function() {
    return {
        "MAX_CHARACTERS": 2000,
        "MAX_POST_FILESIZE": 5242880,
        "MAX_LOGO_FILESIZE": 5242880,
        "MAX_BACKGROUND_FILESIZE": 5242880,
        "MAX_HEADER_FILESIZE": 5242880,
        "MAX_STORAGE_FILESIZE": 41943040
    };
});

app.service("emojis", function() {
    return {
        ":laughing:": "&#128512;",
        ":grinning:": "&#128513;",
        ":tears:": "&#128514;",
        ":sweat:": "&#128517;",
        ":halo:": "&#128519;",
        ":devil:": "&#128520;",
        ":wink:": "&#128521;",
        ":tounge:": "&#128523;",
        ":hearts:": "&#128525;",
        ":cool:": "&#128526;",
        ":smirk:": "&#128527;",
        ":neutral:": "&#128528;",
        ":expressionless:": "&#128529;",
        ":unumused:": "&#128530;",
        ":coldsweat:": "&#128531;",
        ":confused:": "&#128534;",
        ":kiss:": "&#128536;",
        ":winktounge:": "&#128540;",
        ":disappointed:": "&#128542;",
        ":worried:": "&#128543;",
        ":angry:": "&#128544;",
        ":crying:": "&#128546;",
        ":triumph:": "&#128548;",
        ":tired:": "&#128555;",
        ":grimacing:": "&#128556;",
        ":loudlycrying:": "&#128557;",
        ":fear:": "&#128561;",
        ":clown:": "&#129313;",
        ":sick:": "&#129314;",
        ":honk:": "&#129326;",
        ":wtf:": "&#129327;"
    };
});

app.service("transferPrivPosts", function() {

    var transfer = {
        neu: false,
        isNeu: () => { transfer.neu = true },
        notNeu: () => { transfer.neu = false },
        getNeu: () => { return transfer.neu }
    }

    return transfer;
});

app.service("bigPic", function() {

    var pic = {
        show: pic => {
            var showPic = document.querySelector(".show-pic");
            showPic.style.visibility = "visible";

            var bigPic = document.createElement("img");
            bigPic.classList.add("big-pic");
            bigPic.src = pic;

            showPic.innerHTML = "";
            showPic.appendChild(bigPic);
            setTimeout(function() { 
                bigPic.style.transform = "scale(1)";
                bigPic.style.marginTop = "0px";
            }, 50);

            document.body.style.overflow = "hidden";

            showPic.addEventListener("wheel", evt => {
                evt.preventDefault();

                var marginTop;

                if(evt.deltaY < 0) {
                    // zoom in | wheel down
                    marginTop = parseInt(evt.target.style.marginTop);
                    evt.target.style.marginTop = (marginTop+50) + "px";
                }
                else {
                    // zoom out | wheel up
                    marginTop = parseInt(evt.target.style.marginTop);
                    evt.target.style.marginTop = (marginTop-50) + "px";
                }

            });

            showPic.addEventListener("click", evt => {
                showPic.style.visibility = "hidden";
                document.body.style.overflow = "auto";
            });
        }
    }

    return pic;
});

app.service("showInfo", function() {

    var info = {
        ucwords: str => {
            str = str.charAt(0).toUpperCase() + str.substr(1, str.length);
            while(str.indexOf(" ") != -1) {
                var pos = str.indexOf(" ");
                str = str.substr(0, pos) + "_" + str.charAt(pos+1).toUpperCase() + str.substr(pos+2, str.length);
            }
            str = str.replace(/_/g, " ");
            return str;
        },
        show: str => {
            elem = document.getElementById("info");
            if(!elem.classList.contains("show")) {
                elem.style.visibility = "visible";
                elem.innerHTML = str;
                elem.classList.add("show");
                setTimeout(function() {
                    elem.style.visibility = "hidden";
                    elem.classList.remove("show");
                    elem.innerHTML = "";
                }, 3000);
            }
        }
    }

    return info;
});

app.service("checkOnline", function() {

    var info = {
        isOnline: () => {
            return navigator.onLine;
        }
    }

    return info;
});

const embedVideo = (obj, id) => {
    if(obj.innerText === "Embed") {
        obj.insertAdjacentHTML("afterend", "<div class=\"embed-responsive embed-responsive-16by9\"><iframe class=\"embed-responsive-item\" src=\"https://www.youtube-nocookie.com/embed/" + id + "\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe></div>");
        obj.innerText = "Unembed";
    }
    else if(obj.innerText === "Unembed") {
        obj.nextElementSibling.remove();
        obj.innerText = "Embed";
    }
}

app.service("embed", function() {

    var services = {
        link: (text) => {
            const regex = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/g;

            text = text.replace(regex, match => {
                return "<a href=\"" + match + "\" target=\"_blank\">" + match + "</a> ";
            });

            return text;

        },
        youtube: (text) => {
            const regex = /(https?:\/\/(m\.|www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)(.{11}))/g;

            text = text.replace(regex, (match, $1, $2, $3, $4) => {
                return match + " <a href=\"javascript:void(0)\" onclick=\"embedVideo(this, '" + $4 + "')\">Embed</a>";
            });

            return text;
        }
    }

    return services;
});

app.filter("pathFilter", function() {
    return x => {
        var start = x.lastIndexOf("/")+1;
        return x.substr(start, x.length);
    }
});

app.controller("infoCtrl", ($scope, $interval) => {
    $scope.showTime = () => {
        var time = new Date();

        var days = {
            0: "So",
            1: "Mo",
            2: "Di",
            3: "Mi",
            4: "Do",
            5: "Fr",
            6: "Sa"
        };

        var hours = (time.getHours() < 10) ? "0" + time.getHours() : time.getHours();
        var minutes = (time.getMinutes() < 10) ? "0" + time.getMinutes() : time.getMinutes();
        var seconds = (time.getSeconds() < 10) ? "0" + time.getSeconds() : time.getSeconds();

        $scope.time = days[time.getDay()] + " " + hours + ":" + minutes + ":" + seconds;
    };
    $interval($scope.showTime, 10);
});