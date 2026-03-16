// adding fontawesome-kit
const fontawesome = document.createElement("script");
fontawesome.src = "/fontawesome-free-7.2.0-web/js/all.min.js";
document.head.appendChild(fontawesome);

const app = angular.module("tcapp", ["ngSanitize"]);

/**
 * CHANGE CONSTANTS HERE
 */
app.service("constants", function() {
    return {
        "MIN_USERNAME_LENGTH": 4,
        "MAX_USERNAME_LENGTH": 30,
        "MAX_CHARACTERS": 2000,
        "MAX_EVENTIMAGE_FILESIZE": 5242880,
        "MAX_VIDEO_FILESIZE": 41943040
    };
});

app.filter("justDomain", function() {
    return x => {
        if(x != "")
            x = x.match(/\.(\w+)\./)[1];
        
        return x;
    }
});

app.service("easteregg", function($interval) {
    return {
        firework: () => {
            var now = new Date();

            var start1 = new Date("December 25, " + now.getFullYear() + " 00:00:00");
            var end1 = new Date("December 31, " + now.getFullYear() + " 23:59:59");

            var start2 = new Date("January 01, " + now.getFullYear() + " 00:00:00");
            var end2 = new Date("January 06, " + now.getFullYear() + " 23:59:59");

            if(now >= start1 && now <= end1 || now >= start2 && now <= end2) {
                var fireworkScript = document.createElement("script");
                fireworkScript.src = "/scripts/firework.js";
                document.querySelector("head").appendChild(fireworkScript);
            }
        },
        christmas: () => {
            var now = new Date();

            var startChristmas = new Date("December 01, " + now.getFullYear() + " 00:00:00");
            var endChristmas = new Date("December 24, " + now.getFullYear() + " 23:59:59");

            if(now >= startChristmas && now <= endChristmas) {
                var christmas = document.createElement("script");
                christmas.src = "/scripts/christmas.js";
                document.querySelector("head").appendChild(christmas);
            }
        },
        apriljoke: () => {
            var now = new Date();

            var startApril = new Date("April 01, " + now.getFullYear() + " 00:00:00");
            var endApril = new Date("April 01, " + now.getFullYear() + " 23:59:59");

            if(now >= startApril && now <= endApril) {
                var apriljoke = document.createElement("script");
                apriljoke.src = "/scripts/confetti.js";
                document.querySelector("head").appendChild(apriljoke);
            }
        },
        halloween: () => {
            var now = new Date();

            var startHalloween = new Date("October 25, " + now.getFullYear() + " 00:00:00");
            var endHalloween = new Date("October 31, " + now.getFullYear() + " 23:59:59");

            if(now >= startHalloween && now <= endHalloween) {
                var halloweenStyles = document.createElement("link");
                halloweenStyles.href = "/styles/halloween.css";
                halloweenStyles.rel = "stylesheet";
                document.querySelector("head").appendChild(halloweenStyles);

                var container = document.createElement("div");
                container.classList.add("text-center");

                container.innerHTML = `
                    <div class="spider">
                        <span class="eye-left"></span>
                        <span class="eye-right"></span>
                        <span class="leg-left"></span>
                        <span class="leg-left"></span>
                        <span class="leg-left"></span>
                        <span class="leg-right"></span>
                        <span class="leg-right"></span>
                        <span class="leg-right"></span>
                    </div>
            
                    <div class="spider">
                        <span class="eye-left"></span>
                        <span class="eye-right"></span>
                        <span class="leg-left"></span>
                        <span class="leg-left"></span>
                        <span class="leg-left"></span>
                        <span class="leg-right"></span>
                        <span class="leg-right"></span>
                        <span class="leg-right"></span>
                    </div>
            
                    <div class="spider">
                        <span class="eye-left"></span>
                        <span class="eye-right"></span>
                        <span class="leg-left"></span>
                        <span class="leg-left"></span>
                        <span class="leg-left"></span>
                        <span class="leg-right"></span>
                        <span class="leg-right"></span>
                        <span class="leg-right"></span>
                    </div>
            
                    <div class="spider">
                        <span class="eye-left"></span>
                        <span class="eye-right"></span>
                        <span class="leg-left"></span>
                        <span class="leg-left"></span>
                        <span class="leg-left"></span>
                        <span class="leg-right"></span>
                        <span class="leg-right"></span>
                        <span class="leg-right"></span>
                    </div>
                    <h1 style="margin-top:-100px" class="halloween margin-top-middle margin-bottom-small">
                        Happy Halloween!
                    </h1>
                `;
                halloweenStyles.addEventListener("load", () => {
                    document.querySelector(".tabPage").prepend(container);
                });
            }
        },
        tilt: (scope) => {
            if(scope.username === "tilt")
                document.querySelector(".main").classList.add("tilt");
            else
                document.querySelector(".main").classList.remove("tilt");
        },
        barellRoll: (scope) => {
            if(scope.username === "do a barell roll")
                document.querySelector(".main").classList.add("barrel-roll");
            else
                document.querySelector(".main").classList.remove("barrel-roll");
        },
        disco: (scope) => {
            if(scope.username === "disco")
                scope.styles = "disco";
            else
                scope.styles = scope.styles.replace(/disco/g, "");
        },
        matrix: (scope) => {
            if(scope.username === "matrix") {
                var canvas = document.createElement("canvas");
                canvas.id = "matrix";
                document.body.appendChild(canvas);

                document.querySelectorAll(".navbar, .tabPage, .tab").forEach(elem => {
                    elem.style.opacity = 0.5;
                });

                var matrix = document.querySelector("#matrix"),
                ctx = matrix.getContext("2d");

                matrix.width = window.innerWidth;
                matrix.height = window.innerHeight;

                var letters = "ABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZ";
                letters = letters.split('');

                var fontSize = 10,
                    columns = matrix.width / fontSize;
                
                var drops = [];
                for(var i = 0; i < columns; i++) {
                    drops[i] = 1;
                }

                $interval(() => {
                    ctx.fillStyle = "rgba(0, 0, 0, .1)";
                    ctx.fillRect(0, 0, matrix.width, matrix.height);
                    for(var i = 0; i < drops.length; i++) {
                        var text = letters[Math.floor(Math.random() * letters.length)];
                        ctx.fillStyle = "#0f0";
                        ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                        drops[i]++;
                        if(drops[i] * fontSize > matrix.height && Math.random() > .95) {
                            drops[i] = 0;
                        }
                    }
                }, 33);
            }
            else {
                var canvas = document.querySelector("#matrix");
                if(canvas !== null) {
                    canvas.remove();

                    document.querySelectorAll(".navbar, .tabPage, .tab").forEach(elem => {
                        elem.style.opacity = 1;
                    });
                }
            }
        }
    }
});

app.service("postActions", function($http) {
    var actions = {
        showInfo(text) {
            document.querySelector("#infotext").innerText = text;
            document.querySelector("#infoModal").checked = true;
        },
        showEmbedCode: (id) => {
            document.querySelector("#embedtext").innerHTML = "";

            id = (id.sharedid != null) ? id.sharedid : id.postsid;

            var input = document.createElement("input");
            input.id = "embedcode";
            input.classList.add("inputfield");
            input.value = "<iframe style='border: none; width: 350px; height: 600px;' src='https://" + location.hostname + "/embed/post/" + id + "'></iframe>";
            document.querySelector("#embedtext").appendChild(input);

            var button = document.createElement("button");
            button.classList.add("stylish-button");
            button.classList.add("margin-top-small");
            button.innerText = "Copy";
            button.addEventListener("click", event => {
                document.querySelector("#embedcode").select();
                document.execCommand("copy");
            });
            document.querySelector("#embedtext").appendChild(button);

            document.querySelector("#embedModal").checked = true;
        },
        openReport: (blog) => {
            var id = (blog.sharedid != null) ? blog.sharedid : blog.postsid;

            window.open("/report/window?nr=" + id, "", "width=1000,height=500");    
        },
        sharePublicComment: (blog) => {
            var id = (blog.sharedid != null) ? blog.sharedid : blog.postsid;
            var name = (blog.sharedname != null) ? blog.sharedname : blog.username;

            $http({
                url: "/share/publiccomment?id=" + id + "&user=" + name,
                method: "POST"
            }).then(response => {
                actions.showInfo(response.data.info);
            }, error => {
                actions.showInfo(error.data.info);
            });
        }
    }

    return actions;
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
            const regex = /(https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*))/g;

            text = text.replace(regex, (match, $url) => {
                    return `<a href="${$url}" target="_blank">${$url}</a> `;
            });

            return text;

        },
        youtube: (text) => {
            const regex = /(https?:\/\/(m\.|www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)(.{11}))/g;

            text = text.replace(regex, (match, $1, $2, $3, $4) => {
                return match + " <a href=\"javascript:void(0)\" onclick=\"embedVideo(this, '" + $4 + "')\">Embed</a>";
            });

            return text;
        },
        clatcher: (text) => {
            const regex = /https?:\/\/(www\.)?clatcher(\.org|\.xyz)?\/answer\/(\d+)\/\w+/;

            text = text.replace(regex, (match, $1, $2, $id) => {
                return `<iframe style="border: none; margin-left: 25%; margin-right: 25%; width: 50%; min-height: 250px;" src="https://clatcher.org/embed/post/${$id}"></iframe>`;
            });

            return text;
        }
    }

    return services;
});
