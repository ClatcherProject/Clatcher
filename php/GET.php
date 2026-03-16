<?php
class GET {
    public static function readPosts(mysqli $conn, int $pid) {

        $sql = "SELECT users_name, users_logo, posts_id, posts_text, posts_bild, posts_date FROM Users INNER JOIN Posts ON posts_id>? AND users_name=posts_user AND posts_public=1 ORDER BY posts_id ASC LIMIT 100";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            $poststext = Emoji::proceedText($row["posts_text"]);
            $datetime = new DateTime($row["posts_date"]);

            array_push($output, array(
                "username" => $row["users_name"],
                "userlogo" => $row["users_logo"],
                "postsid" => $row["posts_id"],
                "posttext" => $poststext,
                "postbild" => $row["posts_bild"],
                "postdate" => $datetime->format("d.m.Y H:i:s")
            ));
        }

        Response::ok($output);
    }

    public static function readPrivatePosts(mysqli $conn, User $user, int $uid, int $pid) {
        $sql = "SELECT freunde_freundid FROM Freunde WHERE freunde_userid=? AND freunde_freundid=?";

        $userid = $user->getId();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userid, $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0 && $uid != $user->getId() && $user->isAdmin() != 1) {
            Response::forbidden("You are not friends");
            return;
        }

        $stmt = $conn->prepare("SELECT users_name FROM Users WHERE users_id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $result = $result->fetch_assoc();

        $sql = "SELECT users_name, users_logo, posts_id, posts_text, posts_bild, posts_date FROM Users INNER JOIN Posts ON posts_id>? AND users_name=posts_user AND posts_public=0 AND posts_aim=? ORDER BY posts_id ASC LIMIT 100";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $pid, $result["users_name"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            $poststext = Emoji::proceedText($row["posts_text"]);
            $datetime = new DateTime($row["posts_date"]);

            array_push($output, array(
                "username" => $row["users_name"],
                "userlogo" => $row["users_logo"],
                "postsid" => $row["posts_id"],
                "posttext" => $poststext,
                "postbild" => $row["posts_bild"],
                "postdate" => $datetime->format("d.m.Y H:i:s")
            ));
        }

        Response::ok($output);
    }

    public static function loadFiles(mysqli $conn, User $user) {

        $userid = $user->getId();
        $stmt = $conn->prepare("SELECT storage_id, storage_filename FROM Storage WHERE storage_owner=?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            array_push($output, array(
                "sid" => $row["storage_id"],
                "filename" => $row["storage_filename"]
            ));
        }

        Response::ok($output);
    }

    public static function loadRequests(mysqli $conn, User $user, int $id) {
        $sql = "SELECT users_id, users_name, users_logo FROM Users WHERE users_id = ANY(SELECT anfrage_freundid FROM Anfrage WHERE anfrage_userid=?) AND users_id>? ORDER BY users_id LIMIT 10";

        $uid = $user->getId();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $uid, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            array_push($output, array(
                "userid" => $row["users_id"],
                "username" => $row["users_name"],
                "userlogo" => $row["users_logo"]
            ));
        }

        Response::ok($output);
    }

    public static function loadUserThread(mysqli $conn, string $uname) {
        $sql = "SELECT users_id, users_name, users_header FROM Users WHERE users_name=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            array_push($output, array(
                "userid" => $row["users_id"],
                "username" => $row["users_name"],
                "userheader" => $row["users_header"]
            ));
        }

        Response::ok($output);
    }

    public static function loadUser(mysqli $conn, User $user, string $username) {
        $username = "$username%";
        $sql = "SELECT users_id, users_name, users_logo, users_op, users_admin, users_banned FROM Users WHERE users_name LIKE ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            if($user->getId() != $row["users_id"]) {
                array_push($output, array(
                    "userid" => $row["users_id"],
                    "username" => $row["users_name"],
                    "userlogo" => $row["users_logo"],
                    "userop" => $row["users_op"],
                    "useradmin" => $row["users_admin"],
                    "userbanned" => $row["users_banned"]
                ));
            }
        }

        Response::ok($output);
    }

    public static function showFile(mysqli $conn, User $user, int $sid) {
        $userid = $user->getId();
        $stmt = $conn->prepare("SELECT storage_file, storage_type, storage_key FROM Storage WHERE storage_id=? AND storage_owner=?");
        $stmt->bind_param("ii", $sid, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0) {
            Response::forbidden("You cannot see this file");
            return;
        }

        $result = $result->fetch_assoc();

        $file = AES::decrypt($result["storage_file"], $result["storage_key"]);
        header("Content-Type: " . $result["storage_type"]);
        echo($file);
    }

    public static function loadReportedComments(mysqli $conn, User $user) {
        if($user->isOp() != 1 && $user->isAdmin() != 1) {
            Response::forbidden("Unauthorized");
            return;
        }

        $sql = "SELECT report_postid, report_userid, report_grund, report_date, userposts_name, userposts_text, userposts_image FROM Report INNER JOIN Userposts ON userposts_id=report_postid ORDER BY report_id DESC LIMIT 10";

        $result = $conn->query($sql);

        $output = array();

        while($row = $result->fetch_assoc()) {
            $reportedText = Emoji::proceedText($row["userposts_text"]);
            $datetime = new DateTime($row["report_date"]);

            array_push($output, array(
                "postid" => $row["report_postid"],
                "userid" => $row["report_userid"],
                "grund" => $row["report_grund"],
                "date" => $datetime->format("d.m.Y H:i:s"),
                "reportuser" => $row["userposts_name"],
                "reporttext" => $reportedText,
                "reportbild" => $row["userposts_image"]
            ));
        }

        Response::ok($output);
    }

    public static function loadPublicComments(mysqli $conn, string $name, int $id) {
        $sql_std = "SELECT users_logo, userposts_id, userposts_name, userposts_sharedid, userposts_sharedname, userposts_image, userposts_mime, userposts_text, userposts_date FROM Users INNER JOIN Userposts ON userposts_name=? AND users_name=userposts_name ORDER BY userposts_id DESC LIMIT 10";
        $sql_fwd = "SELECT users_logo, userposts_id, userposts_name, userposts_sharedid, userposts_sharedname, userposts_image, userposts_mime, userposts_text, userposts_date FROM Users JOIN Userposts ON userposts_name=? AND userposts_id<? AND users_name=userposts_name ORDER BY userposts_id DESC LIMIT 10";
        
        if($id == 0) {
            $stmt = $conn->prepare($sql_std);
            $stmt->bind_param("s", $name);
        }
        else {
            $stmt = $conn->prepare($sql_fwd);
            $stmt->bind_param("si", $name, $id);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            $poststext = Emoji::proceedText($row["userposts_text"]);
            $datetime = new DateTime($row["userposts_date"]);
            $postbild = ($row["userposts_image"] === NULL) ? NULL : bzdecompress($row["userposts_image"]);

            array_push($output, array(
                "username" => $row["userposts_name"],
                "sharedid" => $row["userposts_sharedid"],
                "sharedname" => $row["userposts_sharedname"],
                "userlogo" => $row["users_logo"],
                "postsid" => $row["userposts_id"],
                "posttext" => $poststext,
                "postbild" => $postbild,
                "mime" => $row["userposts_mime"],
                "postdate" => $datetime->format("d.m.Y H:i:s")
            ));
        }

        Response::ok($output);
    }

    public static function loadFriends(mysqli $conn, User $user, int $id) {
        $userid = $user->getId();
        $sql_std = "SELECT users_id, users_name, users_logo FROM Users WHERE users_id = ANY(SELECT freunde_freundid FROM Freunde WHERE freunde_userid=?) ORDER BY users_id LIMIT 10";
        $sql_fwd = "SELECT users_id, users_name, users_logo FROM Users WHERE users_id = ANY(SELECT freunde_freundid FROM Freunde WHERE freunde_freundid>? AND freunde_userid=?) ORDER BY users_id LIMIT 10";

        if($id == 0) {
            $stmt = $conn->prepare($sql_std);
            $stmt->bind_param("i", $userid);
        }
        else {
            $stmt = $conn->prepare($sql_fwd);
            $stmt->bind_param("ii", $id, $userid);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            array_push($output, array(
                "userid" => $row["users_id"],
                "username" => $row["users_name"],
                "userlogo" => $row["users_logo"]
            ));
        }

        Response::ok($output);
    }

    public static function loadPublicAnswers(mysqli $conn, int $pid, int $id) {
        $sql_std = "SELECT users_logo, useranswers_id, useranswers_name, useranswers_text, useranswers_date FROM Users JOIN Useranswers ON users_name=useranswers_name AND useranswers_postid=? ORDER BY useranswers_id DESC LIMIT 10";
        $sql_fwd = "SELECT users_logo, useranswers_id, useranswers_name, useranswers_text, useranswers_date FROM Users JOIN Useranswers ON users_name=useranswers_name AND useranswers_postid=? AND useranswers_id>? ORDER BY useranswers_id DESC LIMIT 10";

        if($id === 0) {
            $stmt = $conn->prepare($sql_std);
            $stmt->bind_param("i", $pid);
        }
        else {
            $stmt = $conn->prepare($sql_fwd);
            $stmt->bind_param("ii", $pid, $id);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            $poststext = Emoji::proceedText($row["useranswers_text"]);
            $datetime = new DateTime($row["useranswers_date"]);

            array_push($output, array(
                "username" => $row["useranswers_name"],
                "userlogo" => $row["users_logo"],
                "postsid" => $row["useranswers_id"],
                "posttext" => $poststext,
                "postdate" => $datetime->format("d.m.Y H:i:s")
            ));
        }

        Response::ok($output);
    }

    public static function loadMorePublicAnswers(mysqli $conn, int $pid, int $id) {
        $stmt = $conn->prepare("SELECT users_logo, useranswers_id, useranswers_name, useranswers_text, useranswers_date FROM Users JOIN Useranswers ON users_name=useranswers_name AND useranswers_postid=? AND useranswers_id<? ORDER BY useranswers_id DESC LIMIT 10");
        $stmt->bind_param("ii", $pid, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            $poststext = Emoji::proceedText($row["useranswers_text"]);
            $datetime = new DateTime($row["useranswers_date"]);
            array_push($output, array(
                "username" => $row["useranswers_name"],
                "userlogo" => $row["users_logo"],
                "postsid" => $row["useranswers_id"],
                "posttext" => $poststext,
                "postdate" => $datetime->format("d.m.Y H:i:s")
            ));
        }

        Response::ok($output);
    }

    public static function loadShareComments(mysqli $conn) {
        $result = $conn->query("SELECT users_logo, COUNT(userposts_sharedid) AS Anzahl, userposts_sharedid, userposts_sharedname, userposts_image, userposts_mime, userposts_text, userposts_date FROM Userposts JOIN Users ON users_name=userposts_sharedname WHERE userposts_sharedid IS NOT NULL GROUP BY users_logo, userposts_sharedid, userposts_sharedid, userposts_sharedname, userposts_image, userposts_mime, userposts_text, userposts_date ORDER BY Anzahl DESC LIMIT 100");

        $output = array();

        while($row = $result->fetch_assoc()) {
            $poststext = Emoji::proceedText($row["userposts_text"]);
            $datetime = new DateTime($row["userposts_date"]);
            $postbild = ($row["userposts_image"] === NULL) ? NULL : bzdecompress($row["userposts_image"]);

            array_push($output, array(
                "sharedid" => $row["userposts_sharedid"],
                "sharedname" => $row["userposts_sharedname"],
                "userlogo" => $row["users_logo"],
                "anzahl" => $row["Anzahl"],
                "posttext" => $poststext,
                "postbild" => $postbild,
                "mime" => $row["userposts_mime"],
                "postdate" => $datetime->format("d.m.Y H:i:s")
            ));
        }

        Response::ok($output);
    }

    public static function searchPublicComments(mysqli $conn, $id, $text) {
        $text = "%$text%";
        $sql_std = "SELECT users_logo, userposts_id, userposts_name, userposts_image, userposts_mime, userposts_sharedid, userposts_sharedname, userposts_text, userposts_date FROM Users JOIN Userposts ON users_name=userposts_name AND userposts_text LIKE ? AND userposts_sharedid IS NULL ORDER BY userposts_id DESC LIMIT 10";
        $sql_fwd = "SELECT users_logo, userposts_id, userposts_name, userposts_image, userposts_mime, userposts_sharedid, userposts_sharedname, userposts_text, userposts_date FROM Users JOIN Userposts ON userposts_id<? AND userposts_text LIKE ? AND users_name=userposts_name AND userposts_sharedid IS NULL ORDER BY userposts_id DESC LIMIT 10";

        if($id == 0) {
            $stmt = $conn->prepare($sql_std);
            $stmt->bind_param("s", $text);
        }
        else {
            $stmt = $conn->prepare($sql_fwd);
            $stmt->bind_param("is", $id, $text);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            $poststext = Emoji::proceedText($row["userposts_text"]);
            $datetime = new DateTime($row["userposts_date"]);
            $postbild = ($row["userposts_image"] === NULL) ? NULL : bzdecompress($row["userposts_image"]);

            array_push($output, array(
                "username" => $row["userposts_name"],
                "sharedid" => $row["userposts_sharedid"],
                "sharedname" => $row["userposts_sharedname"],
                "userlogo" => $row["users_logo"],
                "postsid" => $row["userposts_id"],
                "posttext" => $poststext,
                "postbild" => $postbild,
                "mime" => $row["userposts_mime"],
                "postdate" => $datetime->format("d.m.Y H:i:s")
            ));
        }

        Response::ok($output);
    }

    public static function loadUsersiteInformation(mysqli $conn, string $username) {
        $username = "%$username%";
        $stmt = $conn->prepare("SELECT users_logo, usersite_name, usersite_birthday, usersite_location, usersite_job, usersite_website FROM Users JOIN Usersite ON users_name=usersite_name AND usersite_name LIKE ? AND users_banned=0 ORDER BY users_date LIMIT 100");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $output = array();

        while($row = $result->fetch_assoc()) {
            if($row["usersite_birthday"] !== NULL)
                $birthday = preg_replace("/(\d{4})-(\d{2})-(\d{2})/", "$3.$2.$1", $row["usersite_birthday"]);
            else
                $birthday = "---";

            array_push($output, array(
                "userlogo" => $row["users_logo"],
                "username" => $row["usersite_name"],
                "birthday" => $birthday,
                "location" => $row["usersite_location"],
                "job" => $row["usersite_job"],
                "website" => $row["usersite_website"]
            ));
        }

        Response::ok($output);
    }

    public static function countUser(mysqli $conn) {
        $result = $conn->query("SELECT COUNT(users_id) AS Anzahl FROM Users");
        Response::ok($result->fetch_assoc()["Anzahl"]);
    }

    public static function embed(mysqli $conn, int $id) {
        $stmt = $conn->prepare("SELECT users_logo, userposts_id, userposts_name, userposts_image, userposts_mime, userposts_text, userposts_date FROM Users JOIN Userposts ON users_name=userposts_name AND userposts_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0) {
            Response::notfound("No such comment");
            return;
        }

        list($logo, $pid, $name, $image, $mime, $text, $date) = $result->fetch_array();
        $text = Emoji::proceedText($text);
        $datetime = new DateTime($date);

        ?>
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <title>Embedded Post</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="robots" content="noindex, nofollow">
                <link rel="stylesheet" href="/styles/index.css">
                <script src="/scripts/angular.min.js"></script>
                <script src="/scripts/angular-sanitize.min.js"></script>
                <script src="/scripts/definitions.js" defer></script>
                <script src="/scripts/embed.js" defer></script>
            </head>
            <body ng-app="tcapp" class="text-light">

                <blockquote class="bg-sci-fi">
                    <header class="blockquote-header">
                        <?php if($logo !== NULL): ?>
                            <img src="<?php echo($logo); ?>" alt="Avatar" class="rounded-circle" width="25" height="25">
                        <?php endif; ?>
                        <span><?php echo($name); ?></span>
                        <span><?php echo( $datetime->format("d.m.Y H:i:s") ); ?></span>
                    </header>
                    <p id="ugc" class="text-light"><?php echo($text); ?></p>
                    <?php if($image != NULL): ?>
                        <?php
                        if($mime == "video/mp4"): ?>
                            <input type="hidden" id="video" value="<?php echo(bzdecompress($image)); ?>">
                            <input type="hidden" id="mime" value="<?php echo($mime); ?>">

                            <div class="embed-responsive embed-responsive-16by9">
                                <video class="embed-responsive-item" controls>
                                    <source id="videosrc" type="video/mp4">
                                </video>
                            </div>

                            <script>
                                const binary = atob(document.getElementById("video").value);
                                const len = binary.length;
                                const bytes = new Uint8Array(len);
                                for(let i = 0; i < len; i++) {
                                    bytes[i] = binary.charCodeAt(i);
                                }
                                const blob = new Blob([bytes], { type: document.getElementById("mime").value });
                                const videoUrl = URL.createObjectURL(blob);
                                document.getElementById("videosrc").src = videoUrl;
                            </script>
                        <?php else: ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <img class="embed-responsive-item" src="<?php echo("data:$mime;base64," . bzdecompress($image)); ?>">
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <footer class="blockquote-footer margin-top-middle">
                        <span><a href="javascript:void(0);" ng-click="openDiscussion(<?php echo("$pid, '$name'"); ?>);">Zur Diskussion</a></span>
                    </footer>
                </blockquote>
            </body>
        </html>
        <?php
    }

    public static function loadUserDiscussion(mysqli $conn, mixed $user, string $name, int $id) {
        $stmt = $conn->prepare("SELECT users_id, users_logo, users_header, userposts_id, userposts_name, userposts_image, userposts_mime, userposts_text, userposts_date FROM Users INNER JOIN Userposts ON userposts_name=? AND users_name=userposts_name AND userposts_id=?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows > 0) {
            list($usersid, $logo, $header, $id, $name, $image, $mime, $text, $date) = $result->fetch_array();
            $poststext = Emoji::proceedText($text);
            $datetime = new DateTime($date);

            ?>
            <!DOCTYPE html>
            <html lang="de">
                <head>
                    <title><?php echo( substr($text, 0, 100) ); ?> - Clatcher</title>
                    <meta charset="utf8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <meta name="description" content="<?php echo( substr($text, 0, 100) ); ?>">
                    <meta name="msapplication-TileColor" content="#303443">
                    <meta name="msapplication-TileImage" content="/favicon.png">
                    <meta name="robots" content="index, follow">
                    <meta name="theme-color" content="#303443">
                    <meta property="og:title" content="Beitrag von <?php echo($name); ?>">
                    <meta property="og:description" content="<?php echo( substr($text, 0, 100) ); ?>">
                    <meta property="og:type" content="Website">
                    <meta property="og:url" content="https://social.clatcher.org/answer/<?php echo("$id/$name"); ?>">
                    <meta property="og:site_name" content="Beitrag von <?php echo($name); ?>">
                    <?php if($image !== NULL): ?>
                        <meta property="og:image" content="<?php echo($header); ?>">
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
                    <script src="/scripts/discussion.js" defer></script>
                </head>
                <body ng-app="tcapp" class="bg-pattern text-light">

                    <!-- Info Modal Window -->
                    <input type="checkbox" id="infoModal" class="close-modal-button">
                    <div id="info" class="modal bg-dark">
                        <div class="modal-header">
                            Information <label for="infoModal"><span class="close">&times;</span></label>
                        </div>
                        <div class="modal-body">
                            <p id="infotext" class="text-light"></p>
                        </div>
                    </div>

                    <main class="main">

                        <nav class="navbar bg-dark text-left">
                            <a class="logo text-light" href="/">
                                Post
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
                            <label for="tab2" accesskey="2"><span class="tab tab-2">Comments</span></label>

                            <div class="tabPage bg-darkblue text-center" data-target="tab1" ng-controller="tab1Ctrl">
                                <?php if($header != NULL): ?>
                                    <header class="ml-n15 mr-n15 mt-n15 margin-bottom-middle" data-image="<?php echo($header); ?>">
                                        <div class="header-info">
                                        </div>
                                    </header>
                                <?php endif; ?>

                                <blockquote id="mainPost" data-target="<?php echo("$id, $name"); ?>" class="margin-top-small margin-bottom-middle">
                                    <header class="blockquote-header">
                                        <?php if($logo !== NULL): ?>
                                            <span><img src="<?php echo($logo); ?>" alt="Avatar" class="rounded-circle" width="40" height="40"></span>
                                        <?php endif; ?>
                                        <span><a href="/<?php echo($name); ?>"><?php echo($name); ?></a></span>
                                        <span><?php echo( $datetime->format("d.m.Y H:i:s") ); ?></span>
                                    </header>
                                    <p id="ugc" class="text-justify"><?php echo($poststext); ?></p>
                                    <?php if($image !== NULL): ?>
                                        <?php if($mime == "video/mp4"): ?>
                                            <input type="hidden" id="video" value="<?php echo(bzdecompress($image)); ?>">
                                            <input type="hidden" id="mime" value="<?php echo($mime); ?>">

                                            <div class="embed-responsive embed-responsive-16by9">
                                                <video class="embed-responsive-item" controls>
                                                    <source id="videosrc" type="video/mp4">
                                                </video>
                                            </div>

                                            <script>
                                                const binary = atob(document.getElementById("video").value);
                                                const len = binary.length;
                                                const bytes = new Uint8Array(len);
                                                for(let i = 0; i < len; i++) {
                                                    bytes[i] = binary.charCodeAt(i);
                                                }
                                                const blob = new Blob([bytes], { type: document.getElementById("mime").value });
                                                const videoUrl = URL.createObjectURL(blob);
                                                document.getElementById("videosrc").src = videoUrl;
                                            </script>
                                        <?php else: ?>
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <img src="<?php echo("data:$mime;base64," . bzdecompress($image)); ?>" class="embed-responsive-item" alt="Postbild">
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </blockquote>


                            </div>

                            <div class="tabPage bg-darkblue text-center" data-target="tab2" ng-controller="tab2Ctrl">
                                <h2>Comments</h2>

                                <blockquote class="margin-bottom-middle">
                                    <header class="blockquote-header">
                                        <span>Antworte diesem Beitrag</span>
                                    </header>
                                    <?php if($user !== NULL): ?>
                                        <?php if($user->isActivated() == 1): ?>
                                            <?php if($user->isBanned() == 0): ?>
                                                <?php
                                                    $u = User::makeUser($conn, $name);
                                                    $uid = $u->getId();
                                                    $userid = $user->getId();

                                                    $stmt = $conn->prepare("SELECT freunde_freundid FROM Freunde WHERE freunde_userid=? AND freunde_freundid=?");
                                                    $stmt->bind_param("ii", $uid, $userid);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    $stmt->close();

                                                    if($result->num_rows > 0 || $uid == $userid || $user->isAdmin() == 1): ?>
                                                        <textarea ng-model="posttext" class="textareafield margin-bottom-small" rows="5" placeholder="Write your answer"></textarea>
                                                        <footer class="blockquote-footer">
                                                            <button class="stylish-button margin-right-small" ng-click="postPublicAnswer();"><i class="fas fa-pencil-alt"></i> Post</button>
                                                            <button class="stylish-button" ng-click="loadPublicAnswers(0);"><i class="fas fa-sync-alt"></i> Reload</button>
                                                        </footer>
                                                    <?php else: ?>
                                                        <p>You are not be friends with <?php echo($name); ?></p>
                                                    <?php endif; ?>
                                            <?php else: ?>
                                                <p>You are banned</p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p>Please confirm your E-Mail</p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p>Sign in <a href="/">here</a> to leave a comment.</p>
                                    <?php endif; ?>
                                </blockquote>

                                <blockquote id="{{x.postsid}}" class="margin-bottom-small" ng-repeat="x in answers">
                                    <header class="blockquote-header">
                                        <img ng-if="x.userlogo != null" ng-src="{{x.userlogo}}" alt="Avatar" class="rounded-circle" height="25" width="25">
                                        <img ng-if="x.userlogo == null" ng-src="/pics/default.png" alt="Avatar" class="rounded-circle" height="25" width="25">
                                        <span>
                                            <a href="/{{x.username}}">{{x.username}}</a>
                                        </span>
                                        <span>{{x.postdate}}</span>
                                    </header>
                                    <p class="text-justify" ng-bind-html="x.posttext"></p>
                                    <footer class="blockquote-footer">
                                        <?php if($user != NULL && ($user->getId() == $usersid || $user->isAdmin() == 1)): ?>
                                            <span>
                                                <a href="javascript:void(0);" class="text-danger" ng-click="deleteAnswer(x);">Löschen</a>
                                            </span>
                                        <?php endif; ?>
                                    </footer>
                                </blockquote>

                                <div class="text-center margin-top-small">
                                    <a ng-if="answers != undefined && empty === false" href="javascript:void(0);" ng-click="loadMorePublicAnswers(answers[answers.length-1].postsid);">Mehr</a>
                                    <p ng-if="empty === true">Keine weiteren Antworten</p>
                                </div>

                            </div>

                        </div>
                    </main>

                </body>
            </html>
            <?php
        }
    }
}
?>
