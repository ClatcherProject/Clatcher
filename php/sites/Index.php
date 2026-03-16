<?php
class Index {
    public static function load() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <title>Clatcher - Customize your own safespace</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="description" content="Clatcher User Dashboard – Chat, Games, Dateien und Medien. Personalisiere dein Profil und steuere deine Posts und Freunde auf einer interaktiven Plattform.">
                <meta name="keywords" content="Blog">
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

                <script src="/scripts/angular.min.js"></script>
                <script src="/scripts/angular-sanitize.min.js"></script>
                <script src="/scripts/definitions.js" defer></script>
                <script src="/scripts/index.js" defer></script>
            </head>
            <body ng-app="tcapp" class="bg-pattern text-light">

            <!--sse-->
            <!-- Login Modal -->
            <input type="checkbox" id="loginModal" class="close-modal-button">
            <div id="login" class="modal" ng-controller="loginCtrl">
                <div class="modal-header">
                    Login <label for="loginModal"><span class="close">&times;</span></label>
                </div>
                <div class="modal-body">
                    <form>
                        <label for="email-login">E-Mail:</label>
                        <input ng-keyDown="keyLogin()" id="email-login" ng-model="mail" class="inputfield margin-bottom-small" type="email" placeholder="E-Mail">

                        <label for="pass">Password:</label>
                        <input ng-keyDown="keyLogin()" id="pass" ng-model="password" class="inputfield margin-bottom-small" type="password" placeholder="Password">

                        <button ng-click="clickLogin();" class="stylish-button">Login</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <p ng-bind="info" class="text-danger"></p>
                </div>
            </div>
            <!--/sse-->

            <!--sse-->
            <!-- Register Modal -->
            <input type="checkbox" id="registerModal" class="close-modal-button">
            <div id="register" class="modal" ng-controller="registerCtrl">
                <div class="modal-header">
                    Register <label for="registerModal"><span class="close">&times;</span></label>
                </div>
                <div class="modal-body">
                    <form>
                        <label for="username">Username:</label>
                        <input ng-keyDown="keyRegister()" id="username" ng-model="username" class="inputfield margin-bottom-small" type="text" placeholder="Username">

                        <label for="email-register">E-Mail:</label>
                        <input ng-keyDown="keyRegister()" id="email-register" ng-model="mail" class="inputfield margin-bottom-small" type="email" placeholder="E-Mail">

                        <label for="pass1">1. Password:</label>
                        <input ng-keyDown="keyRegister()" id="pass1" ng-model="password" class="inputfield margin-bottom-small" type="password" placeholder="Password">

                        <label for="pass2">2. Password:</label>
                        <input ng-keyDown="keyRegister()" id="pass2" ng-model="repeatedpass" class="inputfield margin-bottom-small" type="password" placeholder="Password again">

                        <button ng-click="clickRegister()" class="stylish-button">Register</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <p ng-bind="info" class="text-danger"></p>
                </div>
            </div>
            <!--/sse-->

            <main class="main">

                <nav class="navbar bg-dark text-left">
                    <h1 class="alternate-logo">Clatcher</h1>
                    <input id="navmenu-toggler" type="checkbox" class="navmenu-toggler">
                    <label for="navmenu-toggler" class="navmenu-button">
                        <span class="navmenu-button-symbol" accesskey="m"></span>
                    </label>
    
                    <div class="navmenu">
                        <ul>
                            <li class="navitem">
                                <label for="loginModal" accesskey="l">
                                    <span class="navlink text-light textSize-middle" title="Login"></span>
                                </label>
                            </li>
                            <li class="navitem">
                                <label for="registerModal" accesskey="r">
                                    <span class="navlink text-light textSize-middle" title="Register"></span>
                                </label>
                            </li>
                            <li class="navitem">
                                <a class="navlink text-light textSize-middle" href="/user/blogs" title="Blogs"></a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="body">

                    <input name="tabs" type="radio" class="tabCtrl" id="tab1" checked>
                    <input name="tabs" type="radio" class="tabCtrl" id="tab2">
                    <input name="tabs" type="radio" class="tabCtrl" id="tab3">

                    <label for="tab1" accesskey="1"><span class="tab tab-3 text-light">Welcome</span></label>
                    <label for="tab2" accesskey="2"><span class="tab tab-3 text-light">Usersites</span></label>
                    <label for="tab3" accesskey="3"><span class="tab tab-3 text-light">Info</span></label>

                    <div class="tabPage bg-darkblue text-center" data-target="tab1" ng-controller="tab1Ctrl">

                        <h1 class="margin-bottom-small">Customize your own safespace</h1>

                        <blockquote class="margin-bottom-middle">
                            <header class="blockquote-header">
                                Clatcher – Your Ultimate Personalized Online Platform
                            </header>
                            <p class="text-justify">
                                Create your own Usersite, chat with friends, securely store files, run your blog, and play exciting games – all on one interactive platform. Clatcher is designed for privacy-conscious users who want full control over their content.
                            </p>
			            </blockquote>

                        <blockquote class="margin-bottom-middle">
                            <header class="blockquote-header">
                                Maybe leave some tip
                            </header>
                            <p class="textSize-middle"><i class="fa-brands fa-btc text-bitcoin"></i>itcoin-Address: bc1q9zzdclr4fxespf4zytejs4enp37czvv0syasxq</p>
                            <p>Help us to develop new online games. See <a href="/for/developers#1">here</a></p>
                            <p>Create your own Clatcher-Layer. See <a href="/for/developers#2">here</a></p>
                        </blockquote>

                    </div>

                    <div class="tabPage bg-darkblue text-center" data-target="tab2" ng-controller="tab2Ctrl">

                        <h1 class="margin-bottom-small">Usersite Search</h1>

                        <p>
                            <input type="text" class="inputfield" ng-model="username" placeholder="Username" ng-keyUp="searchUser($event);">
                        </p>

                        <p class="text-secondary text-left">{{users}} users at all and {{info}} usersites</p>

                        <div class="usersites">

                            <blockquote class="usersite text-justify {{styles}} margin-bottom-middle" ng-click="openUsersite(x.username);" ng-repeat="x in userseiten">
                                <a href="/{{x.username}}"></a>
                                <p ng-if="x.job != null"><i class="fas fa-pencil-alt"></i> <span ng-bind-html="x.job"></span></p>
                                <p ng-if="x.job == null"><i class="fas fa-pencil-alt"></i> ---</p>
                                <p ng-if="x.location != null"><i class="fa fa-home fa-fw"></i> <span ng-bind-html="x.location"></span></p>
                                <p ng-if="x.location == null"><i class="fa fa-home fa-fw"></i> ---</p>
                                <p ng-if="x.birthday != null"><i class="fa fa-birthday-cake fa-fw"></i> {{x.birthday}}</p>
                                <p ng-if="x.birthday == null"><i class="fa fa-birthday-cake fa-fw"></i> ---</p>
                                <p ng-if="x.website != null"><i class="fas fa-link fa-fw"></i> <a href="{{x.website}}" target="_blank">{{x.website | justDomain}}</a></p>
                                <p ng-if="x.website == null"><i class="fas fa-link fa-fw"></i> ---</p>

                                <footer class="blockquote-footer">
                                    <img ng-if="x.userlogo != null" class="rounded-circle" ng-src="{{x.userlogo}}" alt="{{x.username}}" height="25" width="25"> <span>{{x.username}}</span>
                                </footer>
                            </blockquote>

                        </div>
                    </div>

                    <div class="tabPage bg-darkblue text-center" data-target="tab3">
                        <h1>What can you do here?</h1>
                        <p class="text-justify">
                            Welcome to my little social network experiment. I want to show you what you can do here and maybe you will find it interesting as well.<br>
                            At first, I want to notice, that I love data sovereignty. So everything you post here, can be deleted at everytime. You decide what you want to share and what not.<br>
                            There is only one rule: No CSAM! You get banned immediately if you get caught.
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            <img style="width: 50%; float: left; margin-right: 5px;" src="/pics/clatcher_background.png" alt="standard background">
                            When you have registered here, you see your personal space, with your standard background. You can change it anytime with whatever you want.<br>
                            In the lower left corner you see the menu button, where you get access to various features of this site.<br>
                            Now, I show you what you can do here precisely.
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            At first, you need to solve a simple captcha to get full access to this platform.<br>
                            Nothing special here, so we go further.
                            <img style="width: 50%; float: right; margin-left: 5px;" src="/pics/clatcher_captcha.png" alt="captcha image">
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            <img style="width: 50%; float: left; margin-right: 5px" src="/pics/clatcher_userinfo.png" alt="user info">
                            Maybe this is the most important layer of your private site. Here, you can activate various features (e. g. your own storage or your public usersite),<br>
                            a link to all public posts, activate your private thread, change your password or logout from your account.
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            Here you can see the public thread. Here can anyone post anything what they want.<br>
                            There are various emojis you can use in the dropdown box below.
                            <img style="width: 50%; float: right; margin-left: 5px;" src="/pics/clatcher_publicthread.png" alt="public thread">
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            <img style="width: 50%; float: left; margin-right: 5px;" src="/pics/clatcher_privatethread.png" alt="private thread">
                            Here is your private thread. Here can post only your friends and you.<br>
                            If a friend is spamming in your private thread, you simply remove him from your friendlist and he cannot post anything there anymore.
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            You also can activate your own file storage. This layer is very versatile. You can upload any file (max. 20 megabyte) into your storage, where any file is encrypted.<br>
                            Then, you can watch your file in your storage any time. So, you can upload mp4-files to watch videos, mp3-files to listen to music, pdf-files to read something, or anything else.<br>
                            You also can upload an html-file and have in your storage a simple browser in browser.
                            <img style="width: 50%; float: right; margin-left: 5px;" src="/pics/clatcher_userstorage.png" alt="userstorage layer">
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            <img style="width: 50%; float: left; margin-right: 5px;" src="/pics/clatcher_userlayer.png" alt="user layer">
                            Here you see the user layer. You can search for all users and send them a friend request.<br>
                            The user can decide if he accepts or not.<br>
                            You also can see your current friends and your current friend requests.
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            Here we see the user settings. You can upload your personal wallpaper for your personal space, your personal logo and your personal header image.<br>
                            Note, that your max upload limit is 5 megabyte.<br>
                            You also can remove everything in your private thread or your whole account on this platform. Everything will be removed, there will be nothing back.
                            <img style="width: 50%; float: right; margin-left: 5px;" src="/pics/clatcher_usersettings.png" alt="user settings">
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            <img style="width: 50%; float: left; margin-right: 5px;" src="/pics/clatcher_gameslayer.png" alt="games layer">
                            You also can play simple games in your private space. If you have any idea what game we should add to the current games list, feel free to contact us :)
                        </p>

                        <p style="display: flex; justify-content: center; align-items: center;" class="text-justify">
                            There are also some webradios you can listen to. If you have any idea what webradio we should add to the list, contact us any time :)
                            <img style="width: 50%; float: right; margin-left: 5px;" src="/pics/clatcher_webradio.png" alt="webradio layer">
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
