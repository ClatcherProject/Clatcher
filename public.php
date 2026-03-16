<?php
    include "php/config.inc.php";
    session_start();

    $conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    $conn->query(INIT_QUERY);

    $uri = substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]));

    if(strpos($uri, "?") !== FALSE) {
        $uri = substr($uri, 0, strpos($uri, "?"));
    }

    if(strpos($uri, "#") !== FALSE) {
        $uri = substr($uri, 0, strpos($uri, "#"));
    }

    $router = new Router();

    $router->add("GET", "#^$#", function() {
        Index::load();
    });

    $router->add("GET", "#^(\w+)$#", function($name) use($conn) {
        Usersite::show($conn, $name);
    });

    $router->add("GET", "#^user/blogs$#", function() {
        Blogs::load();
    });

    $router->add("GET", "#^for/developers$#", function() {
        Developers::load();
    });

    $router->add("GET", "#^share/file$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        ShareFile::load($conn, unserialize($_SESSION["user"]), $_GET["uid"], $_GET["sid"]);
    });

    $router->add("GET", "#^public/posts$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        GET::readPosts($conn, $_GET["postid"]);
    });

    $router->add("GET", "#^private/posts$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        GET::readPrivatePosts($conn, unserialize($_SESSION["user"]), $_GET["userid"], $_GET["postid"]);
    });

    $router->add("GET", "#^user/files$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        GET::loadFiles($conn, unserialize($_SESSION["user"]));
    });

    $router->add("GET", "#^(\d+)/requests$#", function($id) use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        GET::loadRequests($conn, unserialize($_SESSION["user"]), $id);
    });

    $router->add("GET", "#load/userthread$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        GET::loadUserThread($conn, $_GET["uname"]);
    });

    $router->add("GET", "#^load/user$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        GET::loadUser($conn, unserialize($_SESSION["user"]), $_GET["uname"]);
    });

    $router->add("GET", "#^load/background$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        $user = unserialize($_SESSION["user"]);

        if($user->getBackground() !== NULL)
            Response::ok(unserialize($_SESSION["user"])->getBackground());
        else
            Response::ok("/pics/background.png");
    });

    $router->add("GET", "#^show/file$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        GET::showFile($conn, unserialize($_SESSION["user"]), $_GET["sid"]);
    });

    $router->add("GET", "#^reported/comments$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        GET::loadReportedComments($conn, unserialize($_SESSION["user"]));
    });

    $router->add("POST", "#^post/public$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::postComment($conn, unserialize($_SESSION["user"]), $_POST["utext"]);
    });

    $router->add("POST", "#^post/private$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::postPrivateComment($conn, unserialize($_SESSION["user"]), $_POST["uid"], $_POST["utext"]);
    });

    $router->add("POST", "#^file/upload$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::uploadStorageFile($conn, unserialize($_SESSION["user"]));
    });

    $router->add("POST", "#^upload/logo$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::uploadLogo($conn, unserialize($_SESSION["user"]));
    });

    $router->add("POST", "#^upload/background$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::uploadBackground($conn, unserialize($_SESSION["user"]));
    });

    $router->add("POST", "#^upload/header$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::uploadHeader($conn, unserialize($_SESSION["user"]));
    });

    $router->add("POST", "#^activate/publicsite$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::activatePublicSite($conn, unserialize($_SESSION["user"]));
    });

    $router->add("POST", "#^upload/usersettings$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::uploadUserSettings($conn, unserialize($_SESSION["user"]));
    });

    $router->add("POST", "#^post/publiccomment$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::postPublicComment($conn, unserialize($_SESSION["user"]), $_POST["text"]);
    });

    $router->add("POST", "#^post/publicanswer$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::postPublicAnswer($conn, unserialize($_SESSION["user"]), $_POST["text"], $_GET["pid"]);
    });

    $router->add("POST", "#^toggle/op$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::toggleOp($conn, unserialize($_SESSION["user"]), $_GET["uid"]);
    });

    $router->add("POST", "#^toggle/admin$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::toggleAdmin($conn, unserialize($_SESSION["user"]), $_GET["uid"]);
    });

    $router->add("POST", "#^toggle/ban$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::toggleBan($conn, unserialize($_SESSION["user"]), $_GET["uid"]);
    });

    $router->add("POST", "#^share/publiccomment$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        POST::sharePublicComment($conn, unserialize($_SESSION["user"]), $_GET["id"], $_GET["user"]);
    });

    $router->add("POST", "#^copy/files$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        $params = json_decode(file_get_contents("php://input"), TRUE);
        POST::copyFiles($conn, unserialize($_SESSION["user"]), $params["files"], $params["sid"], $params["friendid"]);
    });

    $router->add("PUT", "#^send/request$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        $params = json_decode(file_get_contents("php://input"), TRUE);
        PUT::sendRequest($conn, unserialize($_SESSION["user"]), $params["uid"]);
    });

    $router->add("PUT", "#^refuse/request$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        $params = json_decode(file_get_contents("php://input"), TRUE);
        PUT::refuseRequest($conn, unserialize($_SESSION["user"]), $params["uid"]);
    });

    $router->add("PUT", "#^accept/request$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        $params = json_decode(file_get_contents("php://input"), TRUE);
        PUT::acceptRequest($conn, unserialize($_SESSION["user"]), $params["uid"]);
    });

    $router->add("PUT", "#^activate/email$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        PUT::activateEmail($conn, unserialize($_SESSION["user"]), $_GET["ucode"]);
    });

    $router->add("PUT", "#^send/activatecode$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        PUT::sendCode($conn, unserialize($_SESSION["user"]));
    });

    $router->add("DELETE", "#^remove/file$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        $params = json_decode(file_get_contents("php://input"), TRUE);
        DELETE::removeFile($conn, unserialize($_SESSION["user"]), $params["sid"]);
    });

    $router->add("DELETE", "#^(\w+)/removefriend$#", function($name) use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::removeFriend($conn, unserialize($_SESSION["user"]), User::makeUser($conn, $name));
    });

    $router->add("DELETE", "#^delete/privatethread$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::deletePrivatethread($conn, unserialize($_SESSION["user"]));
    });

    $router->add("DELETE", "#^delete/public$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::deletePublic($conn, unserialize($_SESSION["user"]), $_GET["pid"]);
    });

    $router->add("DELETE", "#^delete/private$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::deletePrivate($conn, unserialize($_SESSION["user"]), $_GET["pid"]);
    });

    $router->add("DELETE", "#^delete/share$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::deleteShare($conn, unserialize($_SESSION["user"]), $_GET["pid"]);
    });

    $router->add("DELETE", "#^delete/comment$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::deleteComment($conn, unserialize($_SESSION["user"]), $_GET["pid"]);
    });

    $router->add("DELETE", "#^delete/report$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::deleteReportedComment($conn, unserialize($_SESSION["user"]), $_GET["pid"]);
    });

    $router->add("DELETE", "#^delete/answer$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::deleteAnswer($conn, unserialize($_SESSION["user"]), $_GET["aid"]);
    });

    $router->add("DELETE", "#^delete/account$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        DELETE::deleteAccount($conn, unserialize($_SESSION["user"]));
    });

    $router->add("GET", "#^load/public/comments$#", function() use($conn) {
        GET::loadPublicComments($conn, $_GET["user"], $_GET["id"]);
    });

    $router->add("GET", "#^answer/(\d+)/(\w+)$#", function($id, $name) use($conn) {
        $user = isset($_SESSION["user"]) ? unserialize($_SESSION["user"]) : NULL;
        GET::loadUserDiscussion($conn, $user, $name, $id);
    });

    $router->add("GET", "#^load/(\w+)/friends$#", function($name) use($conn) {
        $u = User::makeUser($conn, $name);

        if($u->getId() === NULL) {
            Response::notfound("No such user");
            return;
        }

        GET::loadFriends($conn, $u, $_GET["fid"]);
    });

    $router->add("GET", "#^load/public/answers$#", function() use($conn) {
        GET::loadPublicAnswers($conn, $_GET["pid"], $_GET["id"]);
    });

    $router->add("GET", "#^load/morepublic/answers$#", function() use($conn) {
        GET::loadMorePublicAnswers($conn, $_GET["pid"], $_GET["id"]);
    });

    $router->add("GET", "#^load/share/comments$#", function() use($conn) {
        GET::loadShareComments($conn);
    });

    $router->add("GET", "#^search/public/comments$#", function() use($conn) {
        GET::searchPublicComments($conn, $_GET["id"], $_GET["text"]);
    });

    $router->add("GET", "#^embed/post/(\d+)$#", function($id) use($conn) {
        GET::embed($conn, $id);
    });

    $router->add("GET", "#^load/usersite/information$#", function() use($conn) {
        GET::loadUsersiteInformation($conn, $_GET["username"]);
    });

    $router->add("GET", "#^show/user/number$#", function() use($conn) {
        GET::countUser($conn);
    });

    $router->add("DELETE", "#^log/out$#", function() {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        Response::ok("Good Bye");
        session_destroy();
    });

    $router->add("GET", "#^check/signed/in$#", function() {
        if(!isset($_SESSION["user"])) {
            Response::ok(FALSE);
            return;
        }

        Response::ok(TRUE);
    });

    $router->add("GET", "#^private/space$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        Privatespace::load($conn, unserialize($_SESSION["user"]));
    });

    $router->add("GET", "#^report/window$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        Report::load($conn, unserialize($_SESSION["user"]), $_GET["nr"]);
    });

    $router->add("POST", "#^report/comment$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        $params = json_decode(file_get_contents("php://input"), TRUE);
        POST::reportComment($conn, unserialize($_SESSION["user"]), $params["pid"], $params["grund"]);
    });

    $router->add("GET", "#^change/window$#", function() {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        PasswordChanger::load(unserialize($_SESSION["user"]));
    });

    $router->add("PUT", "#^change/password$#", function() use($conn) {
        if(!isset($_SESSION["user"])) {
            Response::forbidden("Not signed in");
            return;
        }

        $params = json_decode(file_get_contents("php://input"), TRUE);
        PUT::changePassword($conn, unserialize($_SESSION["user"]), $params["oldpass"], $params["newpass1"], $params["newpass2"]);
    });

    $router->add("POST", "#^clatcher/log/in$#", function() use($conn) {
        POST::login($conn);
    });

    $router->add("PUT", "#^clatcher/sign/up$#", function() use($conn) {
        PUT::register($conn);
    });

    $router->dispatch($_SERVER["REQUEST_METHOD"], $uri);

    $conn->close();
?>
