<?php
class Developers {
    public static function load() {
        ?>
        <!DOCTYPE html>
<html lang="en">
    <head>
        <title>Clatcher - Information for game developers</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Johannes Müller">
        <meta name="msapplication-TileColor" content="#303443">
        <meta name="msapplication-TileImage" content="/favicon.png">
        <meta name="theme-color" content="#303443">
        <link rel="icon" type="image/x-icon" href="/icon.ico">
        <link rel="shortcut icon" href="/icon.ico">
        <link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicon.png" sizes="96x96">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
        <link rel="stylesheet" type="text/css" href="/styles/index.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.0/styles/default.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.0/highlight.min.js"></script>
        <script src="/scripts/angular.min.js"></script>
        <script src="/scripts/angular-sanitize.min.js"></script>
        <script src="/scripts/definitions.js" defer></script>
        <script src="/scripts/developers.js" defer></script>
    </head>
    <body ng-app="tcapp" class="bg-pattern text-light">

        <main class="main">

            <nav class="navbar bg-dark text-left">
                <a class="logo text-light" href="/">Gamedev</a>
                <input id="navmenu-toggler" type="checkbox" class="navmenu-toggler">
                <label for="navmenu-toggler" class="navmenu-button">
                    <span class="navmenu-button-symbol" accesskey="m"></span>
                </label>
    
                <div class="navmenu">
                    <ul>
                        <li class="navitem">
                            <a class="navlink text-light textSize-middle" href="/user/blogs" title="Blogs"></a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="body">
            
                <input name="tabs" type="radio" class="tabCtrl" id="tab1" checked>
                <input name="tabs" type="radio" class="tabCtrl" id="tab2">

                <label for="tab1" accesskey="1"><span class="tab tab-2">Create Games</span></label>
                <label for="tab2" accesskey="2"><span class="tab tab-2">Create Layers</span></label>

                <div class="tabPage bg-darkblue text-center" data-target="tab1" ng-controller="tab1Ctrl">

                    <p class="text-justify">
                        You develop games yourself, whether as a hobby or professionally, and want to earn some extra money by making your games available to us? Great! I'll explain you in the corresponding tabs what you have to consider.<br>
                    </p>

                    <h2>Important notes</h2>
                    <ul class="text-justify">
                        <li>After you have sent us the source files of your game and we have transferred the money to you, all rights to the game are transferred to us.</li>
                        <li>You can send us your game ideas at any time, but we will not accept every idea. We first check if the game meets our quality standards. If we do not accept the game, we will of course inform you and the source files will be deleted.</li>
                        <li>If we are interested in one of your games, the price we would pay for it is between 50€ and 100€. We will make you a suitable offer and you can decide if you want to accept it or not.</li>
                    </ul>

                    <h2>What do I need to consider?</h2>
                    <p class="text-justify">
                        <ol class="text-justify">
                            <li>You must be at least 18 years old.</li>
                            <li>Send your game idea to this <a href="mailto:johannes.mueller5967@web.de">email address</a>. The mail should contain the following:
                                <ul>
                                    <li>A brief description of what kind of game it is.</li>
                                    <li>A contact information how we can reach you.</li>
                                    <li>Attached is a ZIP package, containing the source files of your game.</li>
                                </ul>
                            </li>
                        </ol>
        
                        <p class="text-justify">
                            After you have sent us an email and you have already changed your mind, you can tell us at any time and we will delete your data immediately.<br>
                            It can take up to 7 days for a response, during which we check your game according to our quality standards.<br>
                            If we are interested in your game, we will discuss the payment details either on the phone or by e-mail.
                        </p>
                    </p>

                    <h2>Any other questions?</h2>
                    <p>
                        If something is still not clear, do not hesitate <a href="mailto:johannes.mueller5967@web.de">to ask us your questions</a>.
                    </p>

                </div>

                <div class="tabPage bg-darkblue text-center" data-target="tab2" ng-controller="tab2Ctrl">
                    <p class="text-justify">
                        Möchtest du eigene Layer zum Clatcher-Privatespace hinzufügen, dann kannst du das ganz einfach tun.<br>
                        Um eigene Layer zu entwickeln, erstellt man quasi nur eine Chrome Extension. Dafür benötigt man die <a href="/Downloadscripts/manifest.json" download>manifest.json</a> 
                        und das <a href="/Downloadscripts/clatcher-script.js" download>clatcher-script.js</a>.<br>
                        Es ist natürlich auch möglich eigene Layer für Firefox zu entwickeln, allerdings könnte sich die Manifestdatei in einigen Punkten unterscheiden. Um ganz sicher zu gehen, lies also auf der <a href="https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/Your_first_WebExtension" target="_blank">Dokumentationsseite</a> von Mozilla die Details nach.<br>
                    </p>

                    <h1>Inhaltsverzeichnis</h1>

                    <ol class="text-justify">
                        <li>
                            <a href="#topic1">Dein erster Clatcher-Layer</a>
                            <ul>
                                <li><a href="#topic1_1">Die Manifestdatei</a></li>
                                <li><a href="#topic1_2">Die index.js</a></li>
                            </ul>
                        </li>
                        <li><a href="#topic2">Toast Benachrichtigungen</a></li>
                    </ol>

                    <h1 id="topic1">Dein erster Clatcher-Layer</h1>

                    <p class="text-justify">
                        Kurz zu Beginn: Ich werde hier anhand eines einfachen Beispiels zeigen wie man eigene Clatcher-Layer erstellt. Ich werde nicht darauf eingehen wie man eigene Chrome Extensions erstellt. Solltest du dich dafür genauer interessieren, dann lies auf der <a href="https://developer.chrome.com/docs/extensions/mv3/getstarted/" target="_blank">Dokumentationsseite</a> von Google genauer nach.<br>
                    </p>

                    <p class="text-justify">
                        Um deinen eigenen Clatcher-Layer zu erstellen, benötigst du zwei Dateien:<br>
                        <ul class="text-justify">
                            <li>Die Manifestdatei: <a href="/Downloadscripts/manifest.json" download>manifest.json</a></li>
                            <li>Das Clatcher-script: <a href="/Downloadscripts/clatcher-script.js" download>clatcher-script.js</a></li>
                        </ul>
                    </p>

                    <p class="text-justify">
                        Mithilfe dieser Dateien werden wir nun einen einfachen Layer erstellen, in dem einfach nur "Hello World!" steht.
                    </p>

                    <h2 id="topic1_1">Die Manifestdatei</h2>

                    <p class="text-justify">
                        Die Manifestdatei hat folgenden Inhalt:<br>
                        <pre>
                            <code class="ĺanguage-javascript text-justify">
                                {
                                    "name": "Clatcher - Hallo Welt",
                                    "description": "First Clatcher Extension Layer",
                                    "version": "1.0",
                                    "icons": {
                                        "128": "favicon.png"
                                    },
                                    "content_scripts": [
                                        {
                                            "matches": ["https://social.clatcher.org/private/space"],
                                            "js": [ "clatcher-script.js", "index.js" ]
                                        }
                                    ],
                                    "manifest_version": 3
                                }
                            </code>
                        </pre>
                        <p class="text-justify">
                            "name" und "description" sollten selbsterklärend sein. Unter "icons" findest du das Icon, welches für die Extension im Browser angezeigt wird. Du kannst dieses <a href="/favicon.png" download>Icon</a> benutzen oder ein anderes.<br>
                            Unter "content_scripts" findest du bei "js" das clatcher-script.js und eine index.js. Auf die Datei index.js werde ich nachher eingehen, es ist nur wichtig zu wissen, dass du die Datei <i>clatcher-script.js</i> zuerst angibst, damit alles Nötige, welches du zur Entwicklung eigener Layer brauchst, geladen wird.<br>
                            Unter "matches" wird spezifiziert, auf welcher Seite die Extension zur Anwendung kommt. Hier solltest nichts ändern, da die Extension nur auf der angegebenen Seite angewendet werden soll.<br>
                            Zuletzt wird unter "manifest_version" die Version angegeben, hier 3. Auch hier sollte es so belassen werden.
                        </p>
                    </p>

                    <h2 id="topic1_2">Die index.js</h2>

                    <p class="text-justify">
                        Die Datei <i>index.js</i> hat den folgenden Inhalt:<br>
                        <pre>
                            <code class="language-javascript text-justify">
                                var helloWorld = new Layer("Hello World", "fa-solid fa-earth-europe", 600);

                                var p = document.createElement("p");
                                p.style.textAlign = "center";
                                p.innerText = "Hello World!";

                                helloWorld.setBody(p);

                                helloWorld.build();
                            </code>
                        </pre>
                        <p class="text-justify">
                            Die Klasse <i>Layer</i> wurde in der Datei <i>clatcher-script.js</i> erstellt und hier instanziiert. Die Argumente für den Konstrukter sind der Titel (dieser wird dann im Header des Layers angezeigt), das Icon (hier werden die CSS-Klassen von <a href="https://fontawesome.com/icons" target="_blank">Fontawesome</a> genutzt. Dazu gleich mehr.) und die Breite.<br>
                            Die Höhe wird automatisch gesetzt, sodass sie nicht extra angegeben werden muss.
                        </p>
                        <p class="text-justify">
                            Bei den Icons von Fontawesome werden derzeit nur die regulären Icons unterstützt und noch nicht die Pro-Icons. Außerdem müssen, wie oben gezeigt, nur die entsprechenden CSS-Klassen an den Konstruktor weitergegeben werden. Für alles weitere kümmert sich die Klasse Layer schon.
                        </p>
                        <p class="text-justify">
                            Anschließend wird die Breite des Layers angegeben. Hier sind es 600 Pixel.
                        </p>
                        <p class="text-justify">
                            Danach wird ein p-Element definiert und darin wird der Text "Hello World!" geschrieben. Dieses p-Element wird nun über die Methode des Objekts <i>helloWorld</i> in den Body des Layers gesetzt.<br>
                            Es ist wichtig zu verstehen, dass die Methode <i>setBody()</i> nichts an den Body anhängt, sondern es übernimmt das Element, welches man übergibt und setzt den Body damit.<br>
                            Würde man beispielsweise mehrmals <i>setBody()</i> aufrufen, würde man nicht mehrere Elemente an den Body anhängen, sondern man würde den Body immer wieder überschreiben. Sollte man selbst mehrere Elemente einfügen wollen, so muss man z. B. ein div-Element erzeugen, dort alle benötigten Elemente anhängen und dann das div-Element der Methode <i>setBody()</i> übergeben.
                        </p>
                        <p class="text-justify">
                            Anschließend wird über die Methode <i>build()</i> das Layer erstellt und in den DOM-Baum des Clatcher-Privatespace eingehängt. Es ist wichtig, dass am Ende die Methode <i>build()</i> aufgerufen wird, denn andernfalls sieht man nichts von deinem Layer, wenn du deine Extension anschließend in deinem Browser installierst.
                        </p>
                        <p class="text-justify">
                            Das Layer sieht am Ende so aus wie im nachfolgenden Bild:<br>
                            <br>
                            <img style="width: 100%;" src="/Downloadscripts/hello_world_layer.png">
                        </p>
                    </p>

                    <h1 id="topic2">Toast-Benachrichtigungen</h1>

                    <p class="text-justify">
                        Toast-Benachrichtigungen sind kurze Textnachrichten, die für kurze Zeit am unteren Bildschirm zu sehen sind. In diesem Abschnitt zeige ich, wieder an einem sehr vereinfachten Beispiel, wie man diese für seine eigenen Clatcher-Layer verwenden kann.
                    </p>

                    <p class="text-justify">
                        Diesmal soll ein Clatcher-Layer erstellt werden, in das ein Button zu sehen ist, der bei einem Klick darauf eine kurze Grussmeldung zurückgibt.<br>
                        Hier also zunächst die manifest.json:
                        <pre>
                            <code class="language-javascript text-justify">
                                {
                                    "name": "Clatcher - Gruss Layer",
                                    "description": "Gruss Clatcher Extension Layer",
                                    "version": "1.0",
                                    "icons": {
                                        "128": "favicon.png"
                                    },
                                    "content_scripts": [
                                        {
                                            "matches": ["https://social.clatcher.org/private/space"],
                                            "js": [ "clatcher-script.js", "index.js" ]
                                        }
                                    ],
                                    "manifest_version": 3
                                }
                            </code>
                        </pre>
                    </p>

                    <p class="text-justify">
                        Bis auf "name" und "description" hat sich hier nichts geändert. Sehen wir uns also die index.js an:
                        <pre>
                            <code class="language-javascript text-justify">
                                var grusslayer = new Layer("Gruss", "fa-solid fa-face-kiss-wink-heart", 300);

                                var btn = grusslayer.getButton("Grüss mich!");
                                
                                btn.addEventListener("click", () => {
                                    grusslayer.showInfo("Hey du ;)");
                                });
                                
                                grusslayer.setBody(btn);
                                
                                grusslayer.build();
                            </code>
                        </pre>
                    </p>

                    <p class="text-justify">
                        Hier können wir direkt zwei Änderungen sehen. Zum Einen, die Methode <i>getButton()</i> mit der wir einen Button erzeugen können, der den typischen Clatcher-Button Style hat. Als Argument wird der Text übergeben, der auf der Schaltfläche zu sehen sein soll.<br>
                        Natürlich muss man nicht einen Button generieren lassen. Man kann sich natürlich auch selbst einen erstellen und diesem seinen eigenen Style verpassen. In der manifest.json kann man auch eigene CSS-Dateien angeben, über die man seine Layer dann auch gestalten kann.
                    </p>

                    <p class="text-justify">
                        Wenn man nun auf diesen Button klickt, wird die Methode <i>showInfo()</i> unseres Layers aufgerufen, der man den anzuzeigenden Text übergibt. <i>showInfo()</i> zeigt eine kurze Meldung an, die dann für 3 Sekunden sichtbar bleibt und anschließend wieder verschwindet.<br>
                        Anschließend wird der Button mit der <i>setBody()</i> Methode des Layers zum Body hinzugefügt und dann mit <i>build()</i> erstellt. Wenn du die App in deinem Browser installierst und mal ausführen lässt, siehst du das Resultat.
                    </p>

                </div>

            </div>
        </main>
    </body>
</html>
        <?php
    }
}
?>
