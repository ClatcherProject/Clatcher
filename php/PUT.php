<?php
class PUT {
    public static function sendRequest(mysqli $conn, User $user, int $id) {
        $userid = $user->getId();

        $stmt = $conn->prepare("SELECT anfrage_freundid FROM Anfrage WHERE anfrage_userid=? AND anfrage_freundid=?");
        $stmt->bind_param("ii", $id, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows > 0) {
            Response::badRequest("Request already sent");
            return;
        }

        $stmt = $conn->prepare("SELECT freunde_freundid FROM Freunde WHERE freunde_userid=? AND freunde_freundid=?");
        $stmt->bind_param("ii", $id, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows > 0) {
            Response::badRequest("You are already friends");
            return;
        }

        $stmt = $conn->prepare("INSERT INTO Anfrage(anfrage_userid, anfrage_freundid) VALUES(?, ?)");
        $stmt->bind_param("ii", $id, $userid);
        $stmt->execute();
        $stmt->close();

        Response::ok("Request sent");
    }

    public static function refuseRequest(mysqli $conn, User $user, int $id) {
        $userid = $user->getId();

        $stmt = $conn->prepare("DELETE FROM Anfrage WHERE anfrage_userid=? AND anfrage_freundid=?");
        $stmt->bind_param("ii", $userid, $id);
        $stmt->execute();
        $stmt->close();

        Response::ok("Request refused");
    }

    public static function acceptRequest(mysqli $conn, User $user, int $id) {
        $userid = $user->getId();

        $stmt = $conn->prepare("INSERT INTO Freunde(freunde_userid, freunde_freundid) VALUES(?, ?)");
        $stmt->bind_param("ii", $userid, $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO Freunde(freunde_userid, freunde_freundid) VALUES(?, ?)");
        $stmt->bind_param("ii", $id, $userid);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM Anfrage WHERE anfrage_userid=? AND anfrage_freundid=?");
        $stmt->bind_param("ii", $userid, $id);
        $stmt->execute();
        $stmt->close();

        Response::ok("Request accepted");
    }

    public static function activateEmail(mysqli $conn, User $user, string $userCode) {
        if($userCode != $user->getActivatecode()) {
            Response::forbidden("Wrong code");
            return;
        }

        $userid = $user->getId();
        $stmt = $conn->prepare("UPDATE Users SET users_activated=1 WHERE users_id=?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $stmt->close();

        $user->setActivated(1);
        $_SESSION["user"] = serialize($user);

        Response::ok("E-Mail confirmed");
    }

    public static function sendCode(mysqli $conn, User $user) {
        if($user->isActivated() != 0) {
            Response::badRequest("Your E-Mail is already activated");
            return;
        }

        srand((double) microtime()*1000000);
        $code = "";

        for($i = 0; $i < 10; $i++) {
            $code .= chr(rand(97, 122));
        }

        $userid = $user->getId();
        $stmt = $conn->prepare("UPDATE Users SET users_activatecode=? WHERE users_id=?");
        $stmt->bind_param("si", $code, $userid);
        $stmt->execute();
        $stmt->close();

        $user->setActivatecode($code);
        $_SESSION["user"] = serialize($user);

        Response::ok($code);
    }

    public static function register(mysqli $conn) {

        $params = json_decode(file_get_contents("php://input"), TRUE);

        if(!isset($params["uname"]) || !isset($params["umail"]) || !isset($params["upass1"]) || !isset($params["upass2"])) {
            Response::badRequest("Invalid parameters");
            return;
        }

        $username = $params["uname"];
        $mail = $params["umail"];
        $upass1 = $params["upass1"];
        $upass2 = $params["upass2"];

        if(strlen($upass1) > 30) {
            Response::forbidden("Password length must not exceed 30 characters");
            return;
        }

        if($upass1 !== $upass2) {
            Response::badRequest("Enter your password twice");
            return;
        }

        if(strlen($username) < MIN_USERNAME_LENGTH || strlen($username) > MAX_USERNAME_LENGTH) {
            Response::badRequest("Username can be at least " . MIN_USERNAME_LENGTH . " and maximum " . MAX_USERNAME_LENGTH . " characters long");
            return;
        }

        if(!preg_match("/[^@]+@[^@]+\.[^@]+/", $mail)) {
            Response::badRequest("Invalid E-Mail");
            return;
        }

        $pass = password_hash($upass1, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO Users(users_name, users_pass, users_mail, users_activated, users_op, users_admin, users_banned) VALUES(?, ?, ?, 0, 0, 0, 0)");
        $stmt->bind_param("sss", $username, $pass, $mail);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            Response::forbidden("This user is already registered here");
            return;
        }

        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM Users WHERE users_name=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        list($id, $name, $pass, $header, $logo, $background, $mail, $activated, $activatecode, $op, $admin, $banned, $date) = $result->fetch_array();
        $user = new User($id, $name, $pass, $header, $logo, $background, $mail, $activated, $activatecode, $op, $admin, $banned, $date);
        $_SESSION["user"] = serialize($user);
        session_regenerate_id();

        Response::ok("success");
    }

    public static function changePassword(mysqli $conn, User $user, $oldpass, $newpass1, $newpass2) {
        $userid = $user->getId();
        $stmt = $conn->prepare("SELECT users_pass FROM Users WHERE users_id=?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $stmt->bind_result($curpass);
        $stmt->fetch();
        $stmt->close();

        if(!password_verify($oldpass, $curpass)) {
            Response::forbidden("Wrong password");
            return;
        }

        if($newpass1 !== $newpass2) {
            Response::forbidden("New passwords different");
            return;
        }

        $newpass1 = password_hash($newpass1, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE Users SET users_pass=? WHERE users_id=?");
        $stmt->bind_param("si", $newpass1, $userid);
        $stmt->execute();
        $stmt->close();

        $user->setPass($newpass1);
        $_SESSION["user"] = serialize($user);
        session_regenerate_id();

        Response::ok("Password changed");
    }
}
?>