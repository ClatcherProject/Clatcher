<?php
class POST {
    public static function postComment(mysqli $conn, User $user, string $text) {
        if($user->isBanned() != 0) {
            Response::forbidden("You are banned");
            return;
        }

        if($user->isActivated() != 1) {
            Response::forbidden("Please confirm your E-Mail");
            return;
        }

        if(strlen($text) <= 0 && !array_key_exists("uimag", $_FILES)) {
            Response::badRequest("No content");
            return;
        }

        if(strlen($text) > MAX_CHARACTERS) {
            Response::badRequest("Maximum " . MAX_CHARACTERS . " characters");
            return;
        }

        $image = array_key_exists("uimag", $_FILES) ? $_FILES["uimag"]["tmp_name"] : NULL;

        if($image !== NULL) {
            if(!Utility::isValidPic(exif_imagetype($image))) {
                Response::badRequest("Only JPEG, PNG or GIF");
                return;
            }

            if(filesize($image) > MAX_POST_FILESIZE) {
                Response::badRequest("Maximum " . (MAX_POST_FILESIZE / (1024*1024)) . " MB");
                return;
            }

            $type = mime_content_type($image);
            $image = base64_encode(file_get_contents($image));
            $image = "data:$type;base64,$image";
        }

        $name = $user->getName();
        $stmt = $conn->prepare("INSERT INTO Posts(posts_user, posts_text, posts_bild, posts_public) VALUES(?, ?, ?, 1)");
        $stmt->bind_param("sss", $name, $text, $image);
        $stmt->execute();
        $stmt->close();

        Response::ok("Post successfully");
    }

    public static function postPrivateComment(mysqli $conn, User $user, int $uid, string $text) {
        if($user->isBanned() != 0) {
            Response::forbidden("You are banned");
            return;
        }

        if($user->isActivated() != 1) {
            Response::forbidden("Please confirm your E-Mail");
            return;
        }

        $userid = $user->getId();
        $stmt = $conn->prepare("SELECT freunde_freundid FROM Freunde WHERE freunde_userid=? AND freunde_freundid=?");
        $stmt->bind_param("ii", $userid, $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0 && $uid != $user->getId() && $user->isAdmin() != 1) {
            Response::forbidden("You are not friends");
            return;
        }

        if($text <= 0 && !array_key_exists("uimag", $_FILES)) {
            Response::badRequest("No content");
            return;
        }

        if(strlen($text) > MAX_CHARACTERS) {
            Response::badRequest("Maximum " . MAX_CHARACTERS . " characters");
            return;
        }

        $image = array_key_exists("uimag", $_FILES) ? $_FILES["uimag"]["tmp_name"] : NULL;

        if($image !== NULL) {
            if(!Utility::isValidPic(exif_imagetype($image))) {
                Response::badRequest("Only JPEG, PNG or GIF");
                return;
            }

            if(filesize($image) > MAX_POST_FILESIZE) {
                Response::badRequest("Maximum " . (MAX_POST_FILESIZE / (1024*1024)) . " MB");
                return;
            }

            $type = mime_content_type($image);
            $image = base64_encode(file_get_contents($image));
            $image = "data:$type;base64,$image";
        }

        $stmt = $conn->prepare("SELECT users_name FROM Users WHERE users_id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $result = $result->fetch_assoc();

        $name = $user->getName();
        $stmt = $conn->prepare("INSERT INTO Posts(posts_user, posts_text, posts_bild, posts_public, posts_aim) VALUES(?, ?, ?, 0, ?)");
        $stmt->bind_param("ssss", $name, $text, $image, $result["users_name"]);
        $stmt->execute();
        $stmt->close();

        Response::ok("Post successfully");
    }

    public static function uploadStorageFile(mysqli $conn, User $user) {

        if(!isset($_FILES["ufile"]["tmp_name"])) {
            Response::badRequest("No file");
            return;
        }

        if(filesize($_FILES["ufile"]["tmp_name"]) > MAX_STORAGE_FILESIZE) {
            Response::badRequest("Maximum " . (MAX_STORAGE_FILESIZE / (1024*1024)) . " MB");
            return;
        }

        if(str_contains($_FILES["ufile"]["name"], "/")) {
            Response::badRequest("'/' not allowed");
            return;
        }

        $key = bin2hex(random_bytes(15));
        $filename = $_FILES["ufile"]["name"];
        $file = AES::encrypt(file_get_contents($_FILES["ufile"]["tmp_name"]), $key);
        $type = mime_content_type($_FILES["ufile"]["tmp_name"]);

        $userid = $user->getId();
        $stmt = $conn->prepare("INSERT INTO Storage(storage_filename, storage_file, storage_type, storage_key, storage_owner) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $filename, $file, $type, $key, $userid);
        $stmt->execute();
        $stmt->close();

        Response::ok("File uploaded");
    }

    public static function uploadLogo(mysqli $conn, User $user) {
        $image = $_FILES["logo"]["tmp_name"];

        if($image == "") {
            Response::badRequest("No file");
            return;
        }

        if(!Utility::isValidPic(exif_imagetype($image))) {
            Response::badRequest("Only JPEG, PNG or GIF");
            return;
        }

        if(filesize($image) > MAX_LOGO_FILESIZE) {
            Response::badRequest("Maximum " . (MAX_LOGO_FILESIZE / (1024*1024)) . " MB");
            return;
        }

        $type = mime_content_type($image);
        $image = base64_encode(file_get_contents($image));
        $image = "data:$type;base64,$image";

        $userid = $user->getId();
        $stmt = $conn->prepare("UPDATE Users SET users_logo=? WHERE users_id=?");
        $stmt->bind_param("si", $image, $userid);
        $stmt->execute();
        $stmt->close();

        $user->setLogo($image);
        $_SESSION["user"] = serialize($user);
        session_regenerate_id();

        Response::ok("Logo updated");
    }

    public static function uploadBackground(mysqli $conn, User $user) {
        $image = $_FILES["background"]["tmp_name"];

        if($image == "") {
            Response::badRequest("No file");
            return;
        }

        if(!Utility::isValidPic(exif_imagetype($image))) {
            Response::badRequest("Only JPEG, PNG or GIF");
            return;
        }

        if(filesize($image) > 5242880) {
            Response::badRequest("Maximum " . (MAX_BACKGROUND_FILESIZE / (1024*1024)) . " MB");
            return;
        }

        $type = mime_content_type($image);
        $image = base64_encode(file_get_contents($image));
        $image = "data:$type;base64,$image";

        $userid = $user->getId();
        $stmt = $conn->prepare("UPDATE Users SET users_background=? WHERE users_id=?");
        $stmt->bind_param("si", $image, $userid);
        $stmt->execute();
        $stmt->close();

        $user->setBackground($image);
        $_SESSION["user"] = serialize($user);
        session_regenerate_id();

        Response::ok($image);
    }

    public static function uploadHeader(mysqli $conn, User $user) {
        $image = $_FILES["header"]["tmp_name"];

        if($image == "") {
            Response::badRequest("No file");
            return;
        }

        if(!Utility::isValidPic(exif_imagetype($image))) {
            Response::badRequest("Only JPEG, PNG or GIF");
            return;
        }

        if(filesize($image) > MAX_HEADER_FILESIZE) {
            Response::badRequest("Maximum " . (MAX_HEADER_FILESIZE / (1024*1024)) . " MB");
            return;
        }

        $type = mime_content_type($image);
        $image = base64_encode(file_get_contents($image));
        $image = "data:$type;base64,$image";

        $userid = $user->getId();
        $stmt = $conn->prepare("UPDATE Users SET users_header=? WHERE users_id=?");
        $stmt->bind_param("si", $image, $userid);
        $stmt->execute();

        $user->setHeader($image);
        $_SESSION["user"] = serialize($user);
        session_regenerate_id();

        Response::ok("Header uploaded");
    }

    public static function activatePublicSite(mysqli $conn, User $user) {
        $username = $user->getName();

        $stmt = $conn->prepare("SELECT usersite_name FROM Usersite WHERE usersite_name=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($user->isActivated() != 1) {
            Response::forbidden("Please confirm your E-Mail");
            return;
        }

        if($result->num_rows > 0) {
            Response::badRequest("Public site is already activated");
            return;
        }

        $stmt = $conn->prepare("INSERT INTO Usersite(usersite_name) VALUES(?)");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        Response::ok("Public site activated");
    }

    public static function uploadUserSettings(mysqli $conn, User $user) {
        $job = htmlentities($_POST["job"], ENT_QUOTES);
        $location = htmlentities($_POST["location"], ENT_QUOTES);
        $birthday = htmlentities($_POST["birthday"], ENT_QUOTES);
        $interests = htmlentities($_POST["interests"], ENT_QUOTES);
        $website = htmlentities($_POST["website"], ENT_QUOTES);
        $website = (preg_match("/^https?:\/\/(\w+|www)\.\w+\.[a-z]{2,5}[\/#\w]*$/", $website) === 1) ? "$website" : NULL;
        $eventtitle = htmlentities($_POST["eventtitle"], ENT_QUOTES);

        $image = isset($_FILES["eventimage"]) ? $_FILES["eventimage"]["tmp_name"] : NULL;

        $username = $user->getName();

        if($image !== NULL) {
            if(filesize($image) > MAX_EVENTIMAGE_FILESIZE) {
                Response::badRequest("Maximum " . (MAX_EVENTIMAGE_FILESIZE / (1024*1024)) . " MB");
                return;
            }

            if(!Utility::isValidPic(exif_imagetype($image))) {
                Response::badRequest("Only JPEG, PNG or GIF");
                return;
            }

            $type = mime_content_type($image);
            $image = base64_encode(file_get_contents($image));
            $image = "data:$type;base64,$image";
        }

        $eventtext = htmlentities($_POST["eventtext"], ENT_QUOTES);
        $job = ($job == "") ? NULL : $job;
        $birthday = ($birthday == "") ? NULL : $birthday;
        $location = ($location == "") ? NULL : $location;

        $stmt = $conn->prepare("UPDATE Usersite SET usersite_job=?, usersite_location=?, usersite_birthday=?, usersite_interests=?, usersite_website=?, usersite_eventimage=?, usersite_eventtitle=?, usersite_eventtext=? WHERE usersite_name=?");
        $stmt->bind_param("sssssssss", $job, $location, $birthday, $interests, $website, $image, $eventtitle, $eventtext, $username);
        $stmt->execute();
        $stmt->close();

        Response::ok("Updated user settings");
    }

    public static function postPublicComment(mysqli $conn, User $user, string $text) {
        if($user->isBanned() != 0) {
            Response::forbidden("You are banned");
            return;
        }

        if($user->isActivated() != 1) {
            Response::forbidden("Please confirm your E-Mail");
            return;
        }

        if(strlen($text) > MAX_CHARACTERS) {
            Response::badRequest("Maximum " . MAX_CHARACTERS . " characters");
            return;
        }

        $text = htmlentities($text, ENT_QUOTES);
        $file = array_key_exists("file", $_FILES) ? $_FILES["file"]["tmp_name"] : NULL;
        $name = $user->getName();
        $type = NULL;

        if($file !== NULL) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $file);

            switch($type) {
                case "video/mp4":
                    if(filesize($file) > MAX_VIDEO_FILESIZE) {
                        Response::badRequest("Maximum " . MAX_VIDEO_FILESIZE . " MB");
                        return;
                    }

                    $getID3 = new getID3;
                    $fileInfo = $getID3->analyze($file);
                    $duration = round($fileInfo["playtime_seconds"]);

                    if($duration > MAX_VIDEO_DURATION) {
                        Response::badRequest("Maximum " . (MAX_VIDEO_DURATION / 60) . " minutes a video");
                        return;
                    }

                    $ext = ".mp4";
                    break;
                case "image/jpg":
                case "image/jpeg":
                case "image/png":
                case "image/gif":
                    if(filesize($file) > MAX_PUBLIC_IMAGE_FILESIZE) {
                        Response::badRequest("Maximum " . (MAX_PUBLIC_IMAGE_FILESIZE / (1024*1024)) . " MB");
                    }
                    break;
                default:
                    Response::badRequest("Only JPEG, PNG, GIF or MP4");
                    return;
            }

            $type = mime_content_type($file);
            $file = bzcompress(base64_encode(file_get_contents($file)), 5);
        }

        $stmt = $conn->prepare("INSERT INTO Userposts(userposts_name, userposts_image, userposts_mime, userposts_text) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $file, $type, $text);
        $stmt->execute();

        Response::ok("Post successfully");
    }

    public static function postPublicAnswer(mysqli $conn, User $user, string $text, int $pid) {
        $stmt = $conn->prepare("SELECT users_id FROM Users WHERE users_name = (SELECT userposts_name FROM Userposts WHERE userposts_id=?)");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->bind_result($uid);
        $stmt->fetch();
        $stmt->close();

        $userid = $user->getId();
        $stmt = $conn->prepare("SELECT freunde_freundid FROM Freunde WHERE freunde_userid=? AND freunde_freundid=?");
        $stmt->bind_param("ii", $uid, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0 && $user->getId() != $uid && $user->isAdmin() != 1) {
            Response::forbidden("You are not friends");
            return;
        }

        if($user->isBanned() != 0) {
            Response::forbidden("You are banned");
            return;
        }

        if($user->isActivated() != 1) {
            Response::forbidden("Please confirm your E-Mail");
            return;
        }

        if(strlen($text) > MAX_CHARACTERS) {
            Response::badRequest("Maximum " . MAX_CHARACTERS . " characters");
            return;
        }

        if($text == "") {
            Response::badRequest("No text");
            return;
        }

        $text = htmlentities($text, ENT_QUOTES);
        $name = $user->getName();

        $stmt = $conn->prepare("INSERT INTO Useranswers(useranswers_postid, useranswers_name, useranswers_text) VALUES(?, ?, ?)");
        $stmt->bind_param("iss", $pid, $name, $text);
        $stmt->execute();
        $stmt->close();

        Response::ok("Post successfully");
    }

    public static function toggleOp(mysqli $conn, User $user, int $uid) {
        if($user->isAdmin() != 1) {
            Response::forbidden("Unauthorized");
            return;
        }

        $stmt = $conn->prepare("SELECT users_op FROM Users WHERE users_id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($uop);
        $stmt->fetch();
        $stmt->close();

        $uop = ($uop === 0) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE Users SET users_op=? WHERE users_id=?");
        $stmt->bind_param("ii", $uop, $uid);
        $stmt->execute();
        $stmt->close();

        if($uop === 1)
            Response::ok("Set OP");
        else
            Response::ok("Removed OP");
    }

    public static function toggleAdmin(mysqli $conn, User $user, int $uid) {
        if($user->isAdmin() != 1) {
            Response::forbidden("Unauthorized");
            return;
        }

        $stmt = $conn->prepare("SELECT users_admin FROM Users WHERE users_id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($uadmin);
        $stmt->fetch();
        $stmt->close();

        $uadmin = ($uadmin === 0) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE Users SET users_admin=? WHERE users_id=?");
        $stmt->bind_param("ii", $uadmin, $uid);
        $stmt->execute();
        $stmt->close();

        if($uadmin === 1)
            Response::ok("Set Admin");
        else
            Response::ok("Remove Admin");
    }

    public static function toggleBan(mysqli $conn, User $user, int $uid) {
        if($user->isAdmin() != 1) {
            Response::forbidden("Unauthorized");
            return;
        }

        $stmt = $conn->prepare("SELECT users_banned FROM Users WHERE users_id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt->bind_result($uban);
        $stmt->fetch();
        $stmt->close();

        $uban = ($uban === 0) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE Users SET users_banned=? WHERE users_id=?");
        $stmt->bind_param("ii", $uban, $uid);
        $stmt->execute();
        $stmt->close();

        if($uban === 1)
            Response::ok("Banned");
        else
            Response::ok("Unbanned");
    }

    public static function sharePublicComment(mysqli $conn, User $user, int $id, string $u) {

        $stmt = $conn->prepare("SELECT userposts_id, userposts_name, userposts_sharedid, userposts_sharedname, userposts_image, userposts_mime, userposts_text FROM Userposts WHERE userposts_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0) {
            Response::badRequest("This post does not exist");
            return;
        }

        list($id, $username, $sharedid, $sharedname, $image, $mime, $text) = $result->fetch_array();
        $name = $user->getName();

        $stmt = $conn->prepare("SELECT COUNT(*) AS Anzahl FROM Usersite WHERE usersite_name=?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $result = $result->fetch_assoc();

        if($user->isBanned() != 0) {
            Response::forbidden("You are banned");
            return;
        }

        if($user->isActivated() != 1) {
            Response::forbidden("Please confirm your E-Mail");
            return;
        }

        if($result["Anzahl"] > 0) {
            $stmt = $conn->prepare("SELECT COUNT(*) AS Anzahl FROM Userposts WHERE userposts_name=? AND userposts_sharedid=?");
            $stmt->bind_param("si", $name, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $result = $result->fetch_assoc();

            if($result["Anzahl"] > 0) {
                Response::forbidden("You already share this post");
                return;
            }

            if($name == $u || $sharedid == $user->getId()) {
                Response::forbidden("You cannot share your own post");
                return;
            }

            $posteduser = $sharedname == NULL ? $username : $sharedname;
            $stmt = $conn->prepare("INSERT INTO Userposts(userposts_name, userposts_sharedid, userposts_sharedname, userposts_image, userposts_mime, userposts_text) VALUES(?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissss", $name, $id, $posteduser, $image, $mime, $text);
            $stmt->execute();
            $stmt->close();

            Response::ok("Post shared");
        }
    }

    public static function login(mysqli $conn) {

        $params = json_decode(file_get_contents("php://input"), TRUE);

        if(!isset($params["umail"]) && !isset($params["upass"])) {
            Response::badRequest("Invalid parameters");
            return;
        }

        $stmt = $conn->prepare("SELECT users_id, users_name, users_pass, users_header, users_logo, users_background, users_mail, users_activated, users_activatecode, users_op, users_admin, users_banned, users_date FROM Users WHERE users_mail=?");
        $stmt->bind_param("s", $params["umail"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0) {
            Response::notfound("No such user");
            return;
        }

        list($id, $name, $pass, $header, $logo, $background, $mail, $activated, $activatecode, $op, $admin, $banned, $date) = $result->fetch_array();

        if($banned != 0) {
            Response::forbidden("This user is banned");
            return;
        }

        if(!password_verify($params["upass"], $pass)) {
            Response::forbidden("Wrong password");
            return;
        }

        $user = new User($id, $name, $pass, $header, $logo, $background, $mail, $activated, $activatecode, $op, $admin, $banned, $date);
        $_SESSION["user"] = serialize($user);
        session_regenerate_id();

        Response::ok("success");
    }

    public static function copyFiles(mysqli $conn, User $user, array $files, int $sid, int $friendid) {
        if(count($files) <= 0) {
            Response::badRequest("No files submitted");
            return;
        }

        $userid = $user->getId();
        $stmt = $conn->prepare("SELECT storage_file, storage_key FROM Storage WHERE storage_id=? AND storage_owner=?");
        $stmt->bind_param("ii", $sid, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $result = $result->fetch_assoc();

        Response::ok("Files shared");
    }

    public static function reportComment(mysqli $conn, User $user, int $pid, string $grund) {
        if($user->isBanned() != 0) {
            Response::forbidden("You are banned");
            return;
        }

        $stmt = $conn->prepare("SELECT report_id FROM Report WHERE report_postid=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows > 0) {
            Response::toomanyrequests("This post were already reported. We will take care of it");
            return;
        }

        $userid = $user->getId();
        $stmt = $conn->prepare("INSERT INTO Report(report_postid, report_userid, report_grund) VALUES(?, ?, ?)");
        $stmt->bind_param("iis", $pid, $userid, $grund);
        $stmt->execute();
        $stmt->close();

        Response::ok("Post reported");
    }
}
?>