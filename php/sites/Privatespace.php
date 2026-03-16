<?php
class Privatespace {
    public static function load(mysqli $conn, User $user) {
        ?>
        <!DOCTYPE html>
        <html lang="de">
        <head>

            <title>Clatcher - <?php echo($user->getName()); ?></title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="theme-color" content="#303443">
            <link rel="icon" type="image/x-icon" href="/icon.ico" />
            <link rel="stylesheet" type="text/css" href="/styles/start.css" />
            <script src="/scripts/angular.min.js"></script>
            <script src="/scripts/angular-sanitize.min.js"></script>
            <script src="/scripts/start/start-definition.js" defer></script>
            <script src="/scripts/start/user-info.js" defer></script>
            <script src="/scripts/start/comment-layer.js" defer></script>
            <script src="/scripts/start/private-comment-layer.js" defer></script>
            <script src="/scripts/start/user-layer.js" defer></script>
            <script src="/scripts/start/user-storage.js" defer></script>
            <script src="/scripts/start/games.js" defer></script>
            <script src="/scripts/start/user-images.js" defer></script>
            <script src="/scripts/start/webradio.js" defer></script>
            <script src="/scripts/start/start-dragelement.js" defer></script>
        </head>
        <body ng-app="tcapp">
            <div class="page-container">

                <div id="info">
                </div>

                <div class="show-pic">
                </div>

                <input type="checkbox" id="user-info-ctrl" class="btnCtrl" checked>
                <div id="user-info" class="clatcher-darktheme" ng-controller="userInfoCtrl">
                    <div id="user-info-header">
                        <div id="user-info-icon"><i class="fas fa-info-circle"></i></div>
                        <div id="user-info-title">User Info</div>
                        <label class="min-button" for="user-info-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="user-info-body" class="anim-border">
                        <?php if($user->getLogo() != NULL): ?>
                            <p>
                                <img style="border-radius: 50%;" width="90" height="80" alt="Avatar" src="<?php echo($user->getLogo()); ?>" />
                            </p>
                        <?php else: ?>
                            <p>
                                <img style="border-radius: 50%;" width="90" height="90" alt="Avatar" src="/pics/default.png">
                            </p>
                        <?php endif; ?>

                        <p id="greeting">Hallo <?php echo($user->getName()); ?></p>

                        <ul id="main-menu">                        

                        <?php if($user->hasUsersite($conn)): ?>
                            <li class="menulist"><a href="/<?php echo($user->getName()); ?>" target="neu"><i class="fas fa-users"></i> Your Public Site</a></li>
                        <?php else: ?>
                            <li class="menulist"><a href="#" ng-click="activatePublicSite();"><i class="fas fa-users"></i> Activate your Public Site</a></li>
                        <?php endif; ?>

                        <li class="menulist"><a href="/user/blogs" target="_blank"><i class="fas fa-rss-square"></i> Blogs</a></li>
                        <li class="menulist"><a href="#" ng-click="loadUserThread('<?php echo($user->getName()) ?>');"><i class="fas fa-user-lock"></i> Your Thread</a></li>
                        <li class="menulist"><a href="#" ng-click="changePass();"><i class="fas fa-key"></i> Change password</a></li>
                        <li class="menulist"><a href="#" ng-click="logout();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul><br />

                        <div class="select-wrapper">
                            <i class="fas fa-caret-down"></i>
                            <select id="themeselector">
                                <option value="dark">Dark Theme</option>
                                <option value="light">Light Theme</option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="checkbox" id="comment-layer-ctrl" class="btnCtrl" checked>
                <div id="comment-layer" class="clatcher-darktheme" ng-controller="postsCtrl">
                    <div id="comment-layer-header">
                        <div id="comment-layer-icon"><i class="far fa-comments"></i></div>
                        <div id="comment-layer-title">User Posts</div>
                        <label class="min-button" for="comment-layer-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="comment-layer-body" class="anim-border">

                        <p>
                            [<a href="javascript:void(0);" ng-click="updatePosts();">Update</a>] Auto: {{ timer / 1000 }}
                        </p>
                        <div id="comments">
                            <div ng-repeat="x in posts" id="{{x.postsid}}" class="container">
                                <img ng-if="x.userlogo != null" width="40" height="40" ng-src="{{x.userlogo}}" alt="Avatar">
                                <img ng-if="x.userlogo == null" width="40" height="40" ng-src="/pics/default.png" alt="Avatar">
                                <p>
                                    <a ng-if="x.postbild != null" href="#" ng-click="showPic(x.postbild)">
                                        <img class="pic" ng-src="{{x.postbild}}" alt="Post_image">
                                    </a>
                                </p>
                                <p ng-bind-html="x.posttext"></p>
                                <span class="time-right">
                                    <a href="/{{x.username}}" target="_blank">{{x.username}}</a> {{x.postdate}}
                                    <a href="javascript:void(0);" class="text-danger" ng-click="removePost(x.postsid)"><i class="fa-solid fa-trash-can"></i></a>
                                </span>
                            </div>
                        </div><br>
                        
                        <textarea ng-model="text" rows="1" placeholder="Schreib etwas..."></textarea><br>

                        <div class="input-group">
                        <button class="stylish" ng-click="postComment();"><i class="fas fa-pencil-alt"></i> {{postsStatus}}</button>
                        
                        <input id="file-upload" style="display: none;" type="file">
                        <label for="file-upload" id="file-upload-button" class="stylish">
                            <i class="far fa-file-image"></i> Choose File
                        </label>
                        </div><br>

                        <div class="select-wrapper">
                            <i class="fas fa-caret-down"></i>
                            <select id="publicemojis">
                                <option value="_blank">Emojis</option>
                                <option ng-repeat="(key, value) in emojis" value="{{key}}" ng-bind-html="value"></option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="checkbox" id="private-comment-layer-ctrl" class="btnCtrl">
                <div id="private-comment-layer" class="clatcher-darktheme" ng-controller="privpostsCtrl">
                    <div id="private-comment-layer-header">
                        <div id="private-comment-layer-icon"><i class="fas fa-comments"></i></div>
                        <div id="private-comment-layer-title">Private User Posts | <?php echo($user->getName()); ?></div>
                        <label class="min-button" for="private-comment-layer-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="private-comment-layer-body" class="anim-border">
                        <?php if($user->getHeader() != NULL): ?>
                            <img id="privateheader" class="mt-n15 header-width" height="100" alt="Header" src="<?php echo($user->getHeader()) ?>" />
                        <?php endif; ?>

                        <p>
                            [<a href="javascript:void(0);" ng-click="updatePrivatePosts();">Update</a>] Auto: {{ timer / 1000 }}
                        </p>
                        <div id="privatecomments" class="<?php echo($user->getId()) ?>"); ?>
                            <div ng-repeat="x in posts" id="{{x.postsid}}" class="container">
                                <img ng-if="x.userlogo != null" width="40" height="40" ng-src="{{x.userlogo}}" alt="Avatar">
                                <img ng-if="x.userlogo == null" width="40" height="40" src="/pics/default.png" alt="Avatar">
                                <p>
                                    <a href="#" ng-click="showPic(x.postbild)"><img ng-if="x.postbild != null" class="pic" ng-src="{{x.postbild}}" alt="Post_image"></a>
                                </p>
                                <p ng-bind-html="x.posttext"></p>
                                <span class="time-right">
                                    <a href="/{{x.username}}" target="_blank">{{x.username}}</a> {{x.postdate}}
                                    <a href="javascript:void(0);" class="text-danger" ng-click="removePost(x.postsid)"><i class="fa-solid fa-trash-can"></i></a>
                                </span>
                            </div>
                        </div>
                        <br>

                        <textarea ng-model="text" rows="1" placeholder="Schreib etwas..."></textarea><br>

                        <div class="input-group">
                        <button ng-click="postComment();" class="stylish"><i class="fas fa-pencil-alt"></i> {{postsStatus}}</button>

                        <input id="private-file-upload" style="display: none;" type="file" />
                        <label for="private-file-upload" id="private-file-upload-button" class="stylish">
                            <i class="far fa-file-image"></i> Choose File
                        </label>
                        </div><br>

                        <div class="select-wrapper">
                            <i class="fas fa-caret-down"></i>
                            <select id="privateemojis">
                                <option value="_blank">Emojis</option>
                                <option ng-repeat="(key, value) in emojis" value="{{key}}" ng-bind-html="value"></option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="checkbox" id="user-layer-ctrl" class="btnCtrl">
                <div id="user-layer" class="clatcher-darktheme" ng-controller="userlayerCtrl">
                    <div id="user-layer-header">
                        <div id="user-layer-icon"><i class="fas fa-users"></i></div>
                        <div id="user-layer-title">Search User</div>
                        <label class="min-button" for="user-layer-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="user-layer-body" class="anim-border">
                        <div class="accordion-title">
                            Search User
                        </div>
                        <div class="accordion-body close-accordion">
                            <input id="searchuserfield" class="textfield w-100" type="text" placeholder="Username" ng-keyup="searchUser()" />
                            <div class="table-wrapper">
                                <table>
                                    <tr ng-repeat="x in users">
                                        <td>
                                            <img ng-if="x.userlogo != null" style="border-radius: 50%;" width="30" height="30" ng-src="{{x.userlogo}}" alt="userlogo">
                                            <img ng-if="x.userlogo == null" style="border-radius: 50%;" width="30" height="30" ng-src="/pics/default.png" alt="userlogo">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" ng-click="loadUserThread(x.username);">{{x.username}}</a>
                                        </td>
                                        <td>
                                            <a href="/{{x.username}}" title="Usersite of {{x.username}}" target="_blank"><i class="fa-solid fa-link"></i></a>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" ng-click="sendAnfrage(x.userid);" title="Friend Request"><i class="fas fa-user-plus"></i></a>
                                        </td>
                                        <?php if($user->isAdmin() == 1): ?>
                                            <td>
                                                <a ng-if="x.userop == 0" class="text-success" href="javascript:void(0);" ng-click="toggleOP(x.userid);" title="Set OP-Rights"><i class="fa-solid fa-circle"></i></a>
                                                <a ng-if="x.userop == 1" class="text-danger" href="javascript:void(0);" ng-click="toggleOP(x.userid);" title="Remove OP-Rights"><i class="fa-solid fa-circle"></i></a>
                                            </td>
                                            <td>
                                                <a ng-if="x.useradmin == 0" class="text-success" href="javascript:void(0);" ng-click="toggleAdmin(x.userid);" title="Set Admin-Rights"><i class="fa-solid fa-crown"></i></a>
                                                <a ng-if="x.useradmin == 1" class="text-danger" href="javascript:void(0);" ng-click="toggleAdmin(x.userid);" title="Remove Admin-Rights"><i class="fa-solid fa-crown"></i></a>
                                            </td>
                                            <td>
                                                <a ng-if="x.userbanned == 0" class="text-danger" href="javascript:void(0);" ng-click="toggleBan(x.userid);" title="Ban User"><i class="fa-solid fa-user"></i></a>
                                                <a ng-if="x.userbanned == 1" class="text-success" href="javascript:void(0);" ng-click="toggleBan(x.userid);" title="Unban User"><i class="fa-solid fa-user"></i></a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="accordion-title">
                            Friends
                        </div>
                        <div class="accordion-body close-accordion">
                            <p><a href="javascript:void(0);" ng-click="loadFriends(0);"><i class="fas fa-sync-alt"></i></a></p>
                            <div class="table-wrapper">
                                <table>
                                    <tr ng-repeat="x in friends track by $index">
                                        <td>
                                            <img ng-if="x.userlogo != null" style="border-radius: 50%;" height="30" width="30" alt="Avatar" ng-src="{{x.userlogo}}">
                                            <img ng-if="x.userlogo == null" style="border-radius: 50%;" height="30" width="30" alt="Avatar" ng-src="/pics/default.png">
                                        </td>
                                        <td><a href="javascript:void(0);" ng-click="loadUserThread(x.username);">{{x.username}}</a></td>
                                        <td><a href="/{{x.username}}" title="Usersite of {{x.username}}" target="_blank"><i class="fa-solid fa-link"></i></a></td>
                                        <td><a title="Remove your friend" href="javascript:void(0);" ng-click="removeFriend(x.username);"><i class="fas fa-user-times"></i></a></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="more-friends">
                                <p><a href="javascript:void(0);" ng-click="loadFriends(friends[friends.length-1].userid)">Mehr</a></p>
                            </div>
                        </div>
                        <div class="accordion-title">
                            Requests
                        </div>
                        <div class="accordion-body close-accordion">
                            <a href="javascript:void(0);" ng-click="loadRequests(0);"><i class="fas fa-sync-alt"></i></a><br>
                            <div class="table-wrapper">
                                <table>
                                    <tr ng-repeat="x in anfragen track by $index">
                                        <td>
                                            <img ng-if="x.userlogo != null" style="border-radius: 50%;" height="30" width="30" alt="Avatar" ng-src="{{x.userlogo}}">
                                            <img ng-if="x.userlogo == null" style="border-radius: 50%;" height="30" width="30" alt="Avatar" ng-src="/pics/default.png">
                                        </td>
                                        <td><a href="javascript:void(0);" ng-click="loadUserThread(x.username);">{{x.username}}</a></td>
                                        <td><a href="/{{x.username}}" title="Usersite of {{x.username}}" target="_blank"><i class="fa-solid fa-link"></i></a></td>
                                        <td><a href="javascript:void(0);" ng-click="acceptRequest(x.userid);"><i class="fas fa-check"></i></a></td>
                                        <td><a href="javascript:void(0);" ng-click="refuseRequest(x.userid);"><i class="fas fa-ban"></i></a></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="more-requests">
                                <p>
                                    <a ng-if="anfragen[anfragen.length-1].userid !== undefined" href="javascript:void(0);" ng-click="loadRequests(anfragen[anfragen.length-1].userid)">Mehr</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="checkbox" id="user-storage-ctrl" class="btnCtrl">
                <div id="user-storage" class="clatcher-darktheme" ng-controller="storageCtrl">
                    <div id="user-storage-header">
                        <div id="user-storage-icon"><i class="fas fa-archive"></i></div>
                        <div id="user-storage-title">Your Storage</div>
                        <label class="min-button" for="user-storage-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="user-storage-body" class="anim-border">

                        <div ng-if="path != null">
                            <div class="file-group mt-n15">
                                <audio ng-if="file == 'audio'" id="audioplayer" controls loop autoplay>
                                    <source ng-if="ext == 'mp3'" ng-src="{{path}}" type="audio/mpeg">
                                    <source ng-if="ext == 'ogg' || ext == 'opus'" ng-src="{{path}}" type="video/ogg">
                                </audio>
                                <video ng-if="file == 'video'" id="videoplayer" controls loop autoplay>
                                    <source ng-src="{{path}}" type="video/{{ext}}">
                                </video>
                                <a ng-if="file == 'image'" href="#" ng-click="showPic(path);">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <img style="cursor: zoom-in;" class="embed-responsive-item" ng-src="{{path}}">
                                    </div>
                                </a>
                                <iframe ng-if="file == 'file'" style="border: none; height: 500px;" ng-src="{{path}}"></iframe>
                                <button id="download-btn" class="stylish" data-bloburl="{{path}}" data-filename="{{filename}}" ng-click="downloadFile();"><i class="fas fa-download"></i></button>
                                <a class="stylish" href="javascript:void(0);" ng-click="clearFile();"><i class="fas fa-times"></i></a>
                            </div>
                        </div>

                        <div class="fileloader mt-15 mb-15"></div>

                        <div class="input-group">
                            <label for="storagefile" id="upload-storagefile" class="stylish">
                                <i class="far fa-file-archive"></i> Choose File
                            </label>
                            <input style="display: none;" id="storagefile" type="file">
                            <button class="stylish" ng-click="uploadYourFile();"><i class="fas fa-upload"></i> {{uploadInfo}}</button>
                        </div>

                        <div class="input-group mt-15">
                            <input ng-model="searchfilter" class="textfield mb-15" type="text" placeholder="Filter">
                            <button class="stylish" ng-click="loadFiles();"><i class="fas fa-cloud-download-alt"></i></button>
                            <button class="stylish" ng-click="hiddenFiles()"><i class="fas fa-eye-slash"></i></button>
                        </div>

                        <div class="table-wrapper">
                            <table>
                                <tr ng-repeat="x in files | filter : searchfilter">
                                    <td><a href="javascript:void(0);" ng-click="getFile(x.filename, x.sid)">{{x.filename}}</a></td>
                                    <td><button class="stylish" ng-click="shareFile(<?php echo($user->getName()); ?>, x.sid);"><i class="fa-solid fa-share"></i></button></td>
                                    <td><button class="stylish" ng-click="removeFile(x.sid)"><i class="fas fa-trash-alt"></i></button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <input type="checkbox" id="games-ctrl" class="btnCtrl">
                <div id="games" class="clatcher-darktheme" ng-controller="gamesCtrl">
                    <div id="games-header">
                        <div id="games-icon"><i class="fas fa-gamepad"></i></div>
                        <div id="games-title">Games</div>
                        <label class="min-button" for="games-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="games-body" class="anim-border">

                        <div id="current-game">
                        </div>
                        <br />
                        <hr />
                        <div class="select-wrapper">
                            <i class="fas fa-caret-down"></i>
                            <select id="gameselector">
                                <option value="0">Games</option>
                                <option value="1">Infinite Mario Bros</option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="checkbox" id="user-settings-ctrl" class="btnCtrl">
                <div id="user-settings" class="clatcher-darktheme" ng-controller="settingsCtrl">
                    <div id="user-settings-header">
                        <div id="user-settings-icon"><i class="fas fa-toolbox"></i></div>
                        <div id="user-settings-title">User Settings</div>
                        <label class="min-button" for="user-settings-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="user-settings-body" class="anim-border">
                        <div class="loader"></div>

                        <div class="accordion-title">
                            Avatar
                        </div>
                        <div class="accordion-body close-accordion">
                            <label for="user-logo" class="stylish"><i class="far fa-image"></i> Avatar</label>
                            <form style="display:inline;" enctype="multipart/form-data">
                                <input style="display:none;" id="user-logo" type="file" />
                            </form>
                            <button class="stylish" ng-click="logoUpload()">{{logoText}}</button>
                            <p>
                                Es kann bis zu 4 Stunden dauern bis Änderungen sichtbar werden.<br>
                                Hat einen Reload zufolge!
                            </p>
                        </div>

                        <div class="accordion-title">
                            Background
                        </div>
                        <div class="accordion-body close-accordion">
                            <label for="user-background" class="stylish"><i class="far fa-image"></i> Background</label>
                            <form style="display:inline;" enctype="multipart/form-data">
                                <input style="display:none;" id="user-background" type="file" />
                            </form>
                            <button class="stylish" ng-click="backgroundUpload()">{{backgroundText}}</button>
                        </div>

                        <div class="accordion-title">
                            Header
                        </div>
                        <div class="accordion-body close-accordion">
                            <label for="user-header" class="stylish"><i class="far fa-image"></i> Header</label>
                            <form style="display:inline;" enctype="multipart/form-data">
                                <input style="display:none;" id="user-header" type="file" />
                            </form>
                            <button class="stylish" ng-click="uploadHeader()">{{headerText}}</button>
                            <p>
                                Es kann bis zu 4 Stunden dauern bis Änderungen sichtbar werden.<br>
                                Hat einen Reload zufolge!
                            </p>
                        </div>

                        <?php if($user->isOp() == 1 || $user->isAdmin() == 1): ?>
                            <div class="accordion-title">
                                Gemeldete öffentliche Posts
                            </div>
                            <div class="accordion-body close-accordion">
                                <button class="stylish" ng-click="loadReportedPosts();">Load Reported Posts</button>
                                <p>
                                    <blockquote ng-repeat="x in reportedPosts track by $index">
                                        <header class="blockquote-header">
                                            <span>
                                                Gemeldeter User: <b>{{x.reportuser}}</b>
                                            </span>
                                            <span>
                                                Grund: <u>{{x.grund}}</u>
                                            </span>
                                            <span>
                                                {{x.date}}
                                            </span>
                                        </header>
                                        <div class="text-justify" ng-bind-html="x.reporttext"></div>
                                        <div ng-if="x.ext != null" class="embed-responsive embed-responsive-16by9">
                                            <img ng-if="x.ext == 'jpeg' || x.ext == 'jpg' || x.ext == 'png' || x.ext == 'gif'" class="embed-responsive-item" ng-src="{{x.reportbild}}">
                                            <video ng-if="x.ext == 'mp4'" class="embed-responsive-item" controls>
                                                <source ng-src="{{x.reportbild}}" type="video/mp4">
                                            </video>
                                        </div>
                                        <footer class="blockquote-footer mt-15">
                                            <span>
                                                <a href="javascript:void(0);" ng-click="deleteComment(x.postid);">Löschen</a>
                                            </span>
                                            <span>
                                                <a href="javascript:void(0);" ng-click="deleteReportComment(x.postid);">Kein Regelverstoß</a>
                                            </span>
                                            <span>
                                                <a href="/answer/{{x.postid}}/{{x.reportuser}}" target="_blank">Zum Post</a>
                                            </span>
                                        </footer>
                                    </blockquote>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="accordion-title">
                            Account löschen
                        </div>
                        <div class="accordion-body close-accordion">
                            <button class="stylish" ng-click="deletePrivPosts()">Lösche deinen Privatethread</button>
                            <button class="stylish" ng-click="deleteAccount()">Lösche deinen Account</button>
                        </div>
                    </div>
                </div>

                <input type="checkbox" id="webradio-ctrl" class="btnCtrl">
                <div id="webradio" class="clatcher-darktheme" ng-controller="webradioCtrl">
                    <div id="webradio-header">
                        <div id="webradio-icon"><i class="fas fa-broadcast-tower"></i></div>
                        <div id="webradio-title">Webradio</div>
                        <label class="min-button" for="webradio-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="webradio-body" class="anim-border">
                        <div id="webradio-player">
                            <audio ng-if="currentServer != ''" id="radioplayer" ng-src="{{currentServer}}" controls autoplay></audio>
                        </div>
                        <div class="select-wrapper">
                            <i class="fas fa-caret-down"></i>
                            <select id="radioselector">
                                <option value="_blank">Select Webradio</option>
                                <option ng-repeat="x in servers" value="{{x.url}}">{{x.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <?php if($user->isActivated() == 0): ?>
                <input type="checkbox" id="mailconfirm-ctrl" class="btnCtrl" checked>
                <div id="mailconfirm" class="clatcher-darktheme" ng-controller="mailconfirmCtrl">
                    <div id="mailconfirm-header">
                        <div id="mailconfirm-title">Captcha</div>
                        <label class="min-button" for="mailconfirm-ctrl"><i class="fas fa-window-minimize"></i></label>
                    </div>
                    <div id="mailconfirm-body" class="anim-border">
                        <p>Enter the following code in reverse into the textfield below: <u>{{actCode}}</u></p>
                        <div class="input-group">
                            <input type="text" class="textfield" placeholder="Code" ng-model="code" ng-keydown="valid(code);">
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <div id="panels-bar-phone" class="panels-bar-phone">
                <div class="dropdown">
                    <div class="dropdown-content">
                        <label for="comment-layer-ctrl" accesskey="c"><i class="far fa-comments mr-5"></i> Public <u class="ml-5">C</u>hat</label>
                        <label for="user-info-ctrl" accesskey="i"><i class="fas fa-info-circle mr-5"></i> User <u class="ml-5">I</u>nfo</label>
                        <label for="private-comment-layer-ctrl" accesskey="p"><i class="fas fa-comments mr-5"></i> <u>P</u>rivate Chat</label>
                        <label for="user-layer-ctrl" accesskey="u"><i class="fas fa-users mr-5"></i> <u>U</u>ser Layer</label>
                        <label for="user-storage-ctrl" accesskey="s"><i class="fas fa-archive mr-5"></i> User <u class="ml-5">S</u>torage</label>
                        <label for="user-settings-ctrl" accesskey="t"><i class="fas fa-toolbox mr-5"></i> User Se<u>t</u>tings</label>
                        <label for="games-ctrl" accesskey="g"><i class="fas fa-gamepad mr-5"></i> <u>G</u>ames</label>
                        <label for="webradio-ctrl" accesskey="r"><i class="fas fa-broadcast-tower mr-5"></i> Web<u>r</u>adio</label>
                        <?php if($user->isActivated() == 0): ?>
                        <label for="mailconfirm-ctrl" accesskey="p">Ca<u>p</u>tcha</label>
                        <?php endif; ?>
                    </div>
                    <button class="dropbtn"><i class="fas fa-bars"></i></button>
                </div>

                <div class="info-panels" ng-controller="infoCtrl">
                    <span class="current-time">{{time}}</span>
                </div>
            </div>

        </body>
    </html>
    <?php
    }
}
?>