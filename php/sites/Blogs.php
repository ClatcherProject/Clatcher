<?php
class Blogs {
    public static function load() {
        ?>
        <!DOCTYPE html>
<html lang="de">
    <head>
        <title>Alle Beiträge der User, die einen Blog betreiben.</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Suche nach bestimmten Beiträgen oder schau dir die meist geteilten Beiträge an.">
        <meta name="keywords" content="Beiträge">
        <meta name="msapplication-TileColor" content="#303443">
        <meta name="msapplication-TileImage" content="/favicon.png">
        <meta name="robots" content="index, follow">
        <meta name="theme-color" content="#303443">
        <link rel="icon" type="image/x-icon" href="/icon.ico">
        <link rel="shortcut icon" href="/icon.ico">
        <link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicon.png" sizes="96x96">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
        <link rel="stylesheet" href="/styles/index.css">
        <script src="/scripts/angular.min.js"></script>
        <script src="/scripts/angular-sanitize.min.js"></script>
        <script src="/scripts/definitions.js" defer></script>
        <script src="/scripts/blogs.js" defer></script>
    </head>
    <body ng-app="tcapp" class="bg-pattern text-light">

        <!-- Info Modal Fenster -->
        <input type="checkbox" id="infoModal" class="close-modal-button">
        <div id="info" class="modal">
            <div class="modal-header">
                Information <label for="infoModal"><span class="close">&times;</span></label>
            </div>
            <div class="modal-body">
                <p id="infotext" class="text-light"></p>
            </div>
        </div>

        <!-- Embed Modal Fenster -->
        <input type="checkbox" id="embedModal" class="close-modal-button">
        <div id="embed" class="modal">
            <div class="modal-header">
                Copy Code <label for="embedModal"><span class="close">&times;</span></label>
            </div>
            <div class="modal-body">
                <p id="embedtext" class="text-light"></p>
            </div>
        </div>

        <main class="main">

            <nav class="navbar bg-dark text-left">
                <a class="logo text-light" href="/">Blogs</a>
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

                <label for="tab1" accesskey="1"><span class="tab tab-2">Alle Posts</span></label>
                <label for="tab2" accesskey="2"><span class="tab tab-2">Shared Posts</span></label>

                <div class="tabPage bg-darkblue text-center" data-target="tab1" ng-controller="tab1Ctrl">

                    <div class="text-justify margin-bottom-big">
                        <div class="input-group">
                            <input type="text" class="inputfield margin-bottom-small" placeholder="Search" ng-model="searchtext">
                            <button class="stylish-button" ng-click="search(0);"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    <blockquote id="{{x.postsid}}" class="margin-bottom-middle" ng-repeat="x in blogs track by $index">
                        <header class="blockquote-header textSize-small">
                            <img ng-if="x.userlogo != null" class="rounded-circle" ng-src="{{x.userlogo}}" alt="{{x.username}}" height="25" width="25">
                            <img ng-if="x.userlogo == null" class="rounded-circle" ng-src="/pics/default.png" alt="{{x.username}}" height="25" width="25">
                            <span>
                                <a ng-if="x.sharedid === null" href="/{{x.username}}">{{x.username}}</a>
                                <a ng-if="x.sharedid !== null" href="/{{x.sharedname}}">{{x.sharedname}}</a>
                            </span>
                            <span>{{x.postdate}}</span>
                            <span>
                                <a ng-if="x.sharedid != null" href="/answer/{{x.sharedid}}/{{x.sharedname}}">Zum Beitrag</a>
                                <a ng-if="x.sharedid == null" href="/answer/{{x.postsid}}/{{x.username}}">Zum Beitrag</a>
                            </span>
                        </header>
                        <p class="text-justify" ng-bind-html="x.posttext"></p>
                        <div ng-if="x.postbild != '' && x.video === false" class="embed-responsive embed-responsive-16by9">
                            <img ng-src="{{x.postbild}}" class="embed-responsive-item">
                        </div>
                        <div ng-if="x.postbild != '' && x.video === true" class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" controls>
                                <source ng-src="{{x.postbild}}" type="video/mp4">
                            </video>
                        </div>
                        <footer class="blockquote-footer margin-top-small">
                            <span ng-if="x.sharedid != null">
                                <b><i class="fas fa-share-alt-square"></i> {{x.anzahl}}</b>
                            </span>
                            <span><a href="javascript:void(0);" ng-click="sharePublicComment(x);">Share</a></span>
                            <span><a href="javascript:void(0);" ng-click="showEmbedCode(x);">Embed</a></span>
                            <span><a href="javascript:void(0);" ng-click="openReport(x);">Report</a></span>
                        </footer>
                    </blockquote>

                    <a ng-if="empty === false" href="javascript:void(0);" class="justify-content-centered margin-small" ng-click="search(blogs[blogs.length-1].postsid);">Mehr</a>
                    <div ng-if="empty === true" class="justify-content-centered margin-small">Keine weiteren Posts</div>
                
                </div>

                <div class="tabPage bg-darkblue text-center" data-target="tab2" ng-controller="tab2Ctrl">
                    <h2>Shared Posts</h1>

                    <div class="margin-middle">
                        <button class="stylish-button" ng-click="loadShares();"><i class="fas fa-share-alt-square"></i></button>
                    </div>

                    <p ng-if="blogs != undefined && blogs.length == 0">
                        Kein Suchtreffer
                    </p>

                    <blockquote id="{{x.postsid}}" class="margin-bottom-middle" ng-repeat="x in blogs track by $index">
                        <header class="blockquote-header textSize-small">
                            <img ng-if="x.userlogo != null" class="rounded-circle" ng-src="{{x.userlogo}}" alt="{{x.username}}" height="25" width="25">
                            <span>
                                <a ng-if="x.sharedid === null" href="/{{x.username}}">{{x.username}}</a>
                                <a ng-if="x.sharedid !== null" href="/{{x.sharedname}}">{{x.sharedname}}</a>
                            </span>
                            <span>{{x.postdate}}</span>
                            <span>
                                <a ng-if="x.sharedid != null" href="/answer/{{x.sharedid}}/{{x.sharedname}}">Zum Beitrag</a>
                                <a ng-if="x.sharedid == null" href="/answer/{{x.postsid}}/{{x.username}}">Zum Beitrag</a>
                            </span>
                        </header>
                        <p class="text-justify" ng-bind-html="x.posttext"></p>
                        <div ng-if="x.postbild != '' && x.video === false" class="embed-responsive embed-responsive-16by9">
                            <img ng-src="{{x.postbild}}" class="embed-responsive-item">
                        </div>
                        <div ng-if="x.postbild != '' && x.video === true" class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" controls>
                                <source ng-src="{{x.postbild}}" type="video/mp4">
                            </video>
                        </div>
                        <footer class="blockquote-footer margin-top-small">
                            <span ng-if="x.sharedid != null">
                                <b><i class="fas fa-share-alt-square"></i> {{x.anzahl}}</b>
                            </span>
                            <span><a href="javascript:void(0);" ng-click="sharePublicComment(x);">Share</a></span>
                            <span><a href="javascript:void(0);" ng-click="showEmbedCode(x);">Embed</a></span>
                            <span><a href="javascript:void(0);" ng-click="openReport(x);">Report</a></span>
                        </footer>
                    </blockquote>

                    <a ng-if="empty === false" href="javascript:void(0);" class="justify-content-centered margin-small" ng-click="search(blogs[blogs.length-1].postsid);">Mehr</a>
                    <div ng-if="empty === true" class="justify-content-centered margin-small">Keine weiteren Posts</div>
                </div>

            </div>
        </main>
    </body>
</html>
    <?php
    }
}
?>