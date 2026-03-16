<?php
class Usersite {
    public static function show(mysqli $conn, string $name) {
        $user = isset($_SESSION["user"]) ? unserialize($_SESSION["user"]) : NULL;

        $stmt = $conn->prepare("SELECT usersite_name, usersite_birthday, usersite_location, usersite_job, usersite_interests, usersite_website, usersite_eventimage, usersite_eventtitle, usersite_eventtext, users_id, users_logo, users_header, users_banned, users_date FROM Usersite INNER JOIN Users ON users_name=usersite_name WHERE usersite_name=?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        $stmt->close();
        
        if($result === NULL) {
            self::notfound();
            return;
        }

        ?>
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <title>Öffentliche Seite von <?php echo($name); ?></title>
                <meta charset="utf8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="description" content="Die Usersite von <?php echo($name); ?>, seine öffentlichen Beiträge und Links zu den Diskussionsseiten.">
                <meta name="keywords" content="<?php echo($name); ?>">
                <meta name="msapplication-TileColor" content="#303443">
                <meta name="msapplication-TileImage" content="/favicon.png">
                <meta name="robots" content="index, follow">
                <meta name="theme-color" content="#303443">
                <meta property="og:title" content="Usersite von <?php echo($name); ?>">
                <meta property="og:description" content="Öffentliche Beiträge von <?php echo($name); ?> und seinen Diskussionsseiten.">
                <meta property="og:type" content="Website">
                <meta property="og:url" content="https://social.clatcher.org<?php echo($name); ?>">
                <meta property="og:site_name" content="Usersite von <?php echo($name); ?>">
                <?php if($result["users_header"] != NULL): ?>
                    <meta property="og:image" content="<?php echo($result["users_header"]); ?>">
                <?php endif; ?>
                <link rel="icon" type="image/x-icon" href="/icon.ico">
                <link rel="shortcut icon" href="/icon.ico">
                <link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
                <link rel="icon" type="image/png" href="/favicon.png" sizes="96x96">
                <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
                <link rel="stylesheet" href="/styles/index.css">
                <script src="/scripts/angular.min.js"></script>
                <script src="/scripts/angular-sanitize.min.js"></script>
                <script src="/scripts/definitions.js" defer></script>
                <script src="/scripts/usersite.js" defer></script>
            </head>
            <body ng-app="tcapp" class="bg-pattern text-light">
                
                <!-- Info Modal Window -->
                <input type="checkbox" id="infoModal" class="close-modal-button">
                <div id="info" class="modal">
                    <div class="modal-header">
                        Information <label for="infoModal"><span class="close">&times;</span></label>
                    </div>
                    <div class="modal-body">
                        <p id="infotext" class="text-light"></p>
                    </div>
                </div>

                <!-- Embed Modal Window -->
                <input type="checkbox" id="embedModal" class="close-modal-button">
                <div id="embed" class="modal">
                    <div class="modal-header">
                        Copy Code <label for="embedModal"><span class="close">&times;</span></label>
                    </div>
                    <div class="modal-body">
                        <p id="embedtext" class="text-light"></p>
                    </div>
                </div>

                <?php if($user != NULL && $result["users_id"] == $user->getId()): ?>
                    <input type="checkbox" id="settingsModal" class="close-modal-button">
                    <div id="settings" class="modal" ng-controller="settingsCtrl">
                        <div class="modal-header">
                            Settings <label for="settingsModal"><span class="close">&times;</span></label>
                        </div>
                        <div class="modal-body">
                            <p>
                                <input ng-model="job" data-target="<?php echo($result["usersite_job"]); ?>" type="text" class="inputfield margin-bottom-small" placeholder="Job">
                                <input ng-model="location" data-target="<?php echo($result["usersite_location"]); ?>" type="text" class="inputfield margin-bottom-small" placeholder="Location">
                                <input ng-model="birthday" data-target="<?php echo($result["usersite_birthday"]); ?>" type="date" class="inputfield margin-bottom-small" placeholder="Birthday">
                                <input ng-model="website" data-target="<?php echo($result["usersite_website"]); ?>" type="text" class="inputfield margin-bottom-small" placeholder="Website">
                                <input ng-model="interests" data-target="<?php echo($result["usersite_interests"]); ?>" type="text" class="inputfield margin-bottom-small" placeholder="Interests">
                                <input ng-model="eventtitle" data-target="<?php echo($result["usersite_eventtitle"]); ?>" type="text" class="inputfield margin-bottom-small" placeholder="Event Title">
                                <input id="eventimage" type="file" class="margin-bottom-small">
                                <label for="eventimage" class="stylish-button margin-bottom-middle">Event Image</label>
                                <textarea ng-model="eventtext" data-target="<?php echo($result["usersite_eventtext"]); ?>" rows="5" class="textareafield margin-top-small" placeholder="Event Text"></textarea>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="stylish-button" ng-click="updateSettings();">Update</button>
                        </div>
                    </div>
                <?php endif; ?>

                <main class="main">

                    <nav class="navbar bg-dark text-left">
                        <a class="logo text-light" href="/">
                            <?php if($result["users_logo"] != NULL): ?>
                                <img class="rounded-circle" src="<?php echo($result["users_logo"]); ?>" width="35" height="35">
                            <?php else: ?>
                                <img class="rounded-circle" src="/pics/default.png" width="35" height="35">
                            <?php endif; ?>
                        </a>

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

                        <label for="tab1" accesskey="1"><span class="tab tab-2"><?php echo($name); ?></span></label>
                        <label for="tab2" accesskey="2"><span class="tab tab-2">Posts</span></label>

                        <div class="tabPage bg-darkblue text-center" data-target="tab1" ng-controller="tab1Ctrl">

                            <?php if($result["users_header"] != NULL): ?>
                                <header class="ml-n15 mr-n15 mt-n15" data-image="<?php echo($result["users_header"]); ?>">
                                    <div class="header-info">
                                        <?php if($result["users_banned"] == 1): ?>
                                            <div class="info-field">
                                                <p class="text-danger">Dieser User wurde gesperrt, weil dessen Nutzer gegen die Communityrichtlinien verstoßen hat.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </header>
                            <?php elseif($result["users_banned"] == 1): ?>
                                <header class="ml-n15 mr-n15">
                                    <p class="text-danger">Dieser User wurde gesperrt, weil dessen Nutzer gegen die Communityrichtlinien verstoßen hat.</p>
                                </header>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-small">
                                    <blockquote id="infos" class="margin-bottom-middle">
                                        <header class="blockquote-header">
                                            <span>Userinfo</span>
                                            <?php if($user != NULL && $result["users_id"] == $user->getId()): ?>
                                                <span>
                                                    <a href="javascript:void(0);" ng-click="openSettings();">Settings</a>
                                                </span>
                                            <?php endif; ?>
                                        </header>
                                        <p class="text-left">
                                            <i class="fas fa-pencil-alt fa-fw"></i> <?php echo( $result["usersite_job"] !== NULL ? $result["usersite_job"] : "---"); ?>
                                        </p>
                                        <p class="text-left">
                                            <i class="fa fa-home fa-fw"></i> <?php echo( $result["usersite_location"] !== NULL ? $result["usersite_location"] : "---" ); ?>
                                        </p>
                                        <p class="text-left">
                                            <i class="fa fa-birthday-cake fa-fw"></i> <?php echo( $result["usersite_birthday"] !== NULL ? preg_replace("/(\d{4})-(\d{2})-(\d{2})/", "$3.$2.$1", $result["usersite_birthday"]) : "---" ); ?>
                                        </p>
                                        <p class="text-left">
                                            <i class="fas fa-link fa-fw"></i> <?php echo( $result["usersite_website"] !== NULL ? "<a href=\"" . $result["usersite_website"] . "\" target=\"_blank\">" . preg_replace("/https?:\/\/(www\.|\w+\.)?(\w+)(\..+)?/", "$2", $result["usersite_website"]) . "</a>" : "---" ); ?>
                                        </p>
                                        <footer class="blockquote-footer">
                                            <?php
                                                $year = explode("-", $result["users_date"]);
                                                echo("Dabei seit $year[0]");
                                            ?>
                                        </footer>
                                    </blockquote>

                                    <blockquote id="friends" class="margin-bottom-middle">
                                        <header class="blockquote-header">
                                            <span>Freunde</span>
                                        </header>
                                        <div data-target="<?php echo($name); ?>">
                                            <p ng-repeat="x in friends">
                                                <a class="display-inline" href="/{{x.username}}">
                                                    <img ng-if="x.userlogo != null" class="rounded-circle margin-right-small" height="30" width="30" alt="Avatar" ng-src="{{x.userlogo}}">
                                                    <img ng-if="x.userlogo == null" class="rounded-circle margin-right-small" height="30" width="30" alt="Avatar" ng-src="/pics/default.png">
                                                    {{x.username}}
                                                </a>
                                            </p>
                                            <p class="margin-top-middle" ng-if="friends !== undefined && noFriends === false">
                                                <a href="javascript:void(0);" ng-click="loadFriends(friends[friends.length-1].userid)">Mehr</a>
                                            </p>
                                            <p class="margin-top-middle" ng-if="noFriends === true">
                                                Keine weiteren Freunde
                                            </p>
                                        </div>
                                    </blockquote>

                                    <blockquote id="interests" class="margin-bottom-middle">
                                        <header class="blockquote-header">
                                            <span>Interessen</span>
                                        </header>
                                        <p class="text-left"><?php echo( preg_replace("/ /", ", ", ($result["usersite_interests"] === NULL) ? "" : $result["usersite_interests"]) ); ?></p>
                                    </blockquote>

                                </div>

                                <div class="col-big">
                                    <blockquote>
                                        <header class="blockquote-header">
                                            <span>Aktuelles Event</span>
                                        </header>
                                        <?php if($result["usersite_eventimage"] != ""): ?>
                                            <img src="<?php echo($result["usersite_eventimage"]); ?>" alt="Eventimage" style="width: 100%">
                                        <?php endif; ?>
                                        
                                        <?php if($result["usersite_eventtitle"] != "" || $result["usersite_eventtext"] != ""): ?>
                                            <p id="eventtitle" class="text-justify"><b><?php echo($result["usersite_eventtitle"]); ?></b></p>
					                        <p id="eventtext" class="text-justify"><?php echo( preg_replace("/\n/", "<br>", $result["usersite_eventtext"]) ); ?></p>
                                        <?php else: ?>
                                            <p class="text-justify">Kein Event</p>
                                        <?php endif; ?>
                                    </blockquote>
                                </div>

                            </div>
                        </div>

                        <div class="tabPage bg-darkblue text-center" data-target="tab2" ng-controller="tab2Ctrl">
                            <h2 class="margin-bottom-middle" data-target="<?php echo($name); ?>">Beiträge von <?php echo($name); ?></h2>

                            <?php if($user !== NULL && $result["users_id"] == $user->getId()): ?>
                                <blockquote class="text-right margin-bottom-big">
                                    <header class="blockquote-header">
                                        <span>Verfasse Beitrag</span>
                                    </header>
                                    <textarea ng-model="blogtext" class="textareafield margin-bottom-small" placeholder="Verfasse Beitrag"></textarea>
                                    <input id="blogfile" type="file">
                                    <footer class="blockquote-footer">
                                        <label for="blogfile" class="stylish-button margin-right-small"><i class="far fa-image"></i> Image/Video</label>
                                        <button class="stylish-button" ng-click="postBlog();"><i class="fas fa-pencil-alt"></i> {{uploadInfo}}</button>
                                    </footer>
                                </blockquote>
                            <?php endif; ?>

                            <blockquote id="{{x.postsid}}" class="margin-bottom-middle" ng-repeat="x in blogs">
                                <header class="blockquote-header textSize-small">
                                    <img ng-if="x.userlogo != null" class="rounded-circle" ng-src="{{x.userlogo}}" alt="{{x.username}}" height="25" width="25">
                                    <img ng-if="x.userlogo == null" class="rounded-circle" ng-src="/pics/default.png" alt="{{x.username}}" height="25" width="25">
                                    <span>
                                        <a href="/{{x.username}}">{{x.username}}</a>
                                    </span>
                                    <span>{{x.postdate}}</span>
                                    <span>
                                        <a ng-if="x.sharedid != null" href="/answer/{{x.sharedid}}/{{x.sharedname}}">Zum Beitrag</a>
                                        <a ng-if="x.sharedid == null" href="/answer/{{x.postsid}}/{{x.username}}">Zum Beitrag</a>
                                    </span>
                                </header>
                                <p class="text-justify" ng-bind-html="x.posttext"></p>
                                <div ng-if="x.postbild !== '' && x.video === false" class="embed-responsive embed-responsive-16by9">
                                    <img ng-src="{{x.postbild}}" class="embed-responsive-item">
                                </div>
                                <div ng-if="x.postbild !== '' && x.video === true" class="embed-responsive embed-responsive-16by9">
                                    <video class="embed-responsive-item" controls>
                                        <source ng-src="{{x.postbild}}" type="video/mp4">
                                    </video>
                                </div>
                                <footer class="blockquote-footer margin-top-small">
                                    <span ng-if="x.sharedid != null">
                                        <b><i class="fas fa-share-alt-square"></i> <a href="/{{x.sharedname}}">{{x.sharedname}}</a></b>
                                    </span>
                                    <span><a href="javascript:void(0);" ng-click="sharePublicComment(x);">Share</a></span>
                                    <span><a href="javascript:void(0);" ng-click="showEmbedCode(x);">Embed</a></span>
                                    <span><a href="javascript:void(0);" ng-click="openReport(x);">Report</a></span>
                                    <?php if($user !== NULL && ($result["users_id"] == $user->getId() || $user->isAdmin() == 1)): ?>
                                        <span>
                                            <a ng-if="x.sharedid != null" href="javascript:void(0);" class="text-danger" ng-click="stopSharing(x);">Nicht mehr teilen</a>
                                            <a ng-if="x.sharedid == null" href="javascript:void(0);" class="text-danger" ng-click="deleteComment(x);">Löschen</a>
                                        </span>
                                    <?php endif; ?>
                                </footer>
                            </blockquote>

                            <div class="text-center">
                                <a ng-if="blogs != undefined && empty === false" href="javascript:void(0);" ng-click="loadBlogs(blogs[blogs.length-1].postsid);">Mehr</a>
                                <p ng-if="empty === true">Keine weiteren Posts</p>
                            </div>

                        </div>

                    </div>
                </main>
            </body>
        </html>
        <?php
    }

    public static function notfound() {
        header("HTTP/1.1 404 Not Found");
        ?>
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <title>Diese Seite existiert nicht</title>
                <meta charset="utf8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="msapplication-TileColor" content="#303443">
                <meta name="msapplication-TileImage" content="/favicon.png">
                <meta name="robots" content="noindex, nofollow">
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
                <script src="/scripts/notfound.js" defer></script>
            </head>
            <body ng-app="tcapp" class="bg-pattern text-light">
                <main class="main">
                    
                    <nav class="navbar bg-dark text-left">
                        <a class="logo text-light" href="/">Not Found</a>

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

                        <label for="tab1" accesskey="1">
                            <span class="tab tab-1">404</span>
                        </label>

                        <div class="tabPage bg-darkblue text-center" data-target="tab1" ng-controller="tab1Ctrl">
                            <header class="ml-n15 mr-n15 mt-n15" data-image="/pics/tuxnotfound.jpg">
                                <div class="header-info">
                                </div>
                            </header>

                            <h1 class="margin-top-small">Die angeforderte Usersite existiert nicht</h1>

                        </div>

                    </div>
                </main>
            </body>
        </html>
        <?php
    }
}
?>
