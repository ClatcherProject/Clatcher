<?php
class DELETE {
    public static function removeFile(mysqli $conn, User $user, int $sid) {
        $userid = $user->getId();
        $stmt = $conn->prepare("DELETE FROM Storage WHERE storage_id=? AND storage_owner=?");
        $stmt->bind_param("ii", $sid, $userid);
        $stmt->execute();

        if($stmt->affected_rows <= 0) {
            Response::forbidden("You cannot delete this file");
            return;
        }

        $stmt->close();

        Response::ok("File deleted");
    }

    public static function removeFriend(mysqli $conn, User $user, User $friend) {
        if($friend->getId() === NULL) {
            Response::badRequest("No such user");
            return;
        }

        $fid = $friend->getId();
        $userid = $user->getId();

        $stmt = $conn->prepare("SELECT freunde_freundid FROM Freunde WHERE freunde_userid=? AND freunde_freundid=?");
        $stmt->bind_param("ii", $userid, $fid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0) {
            Response::badRequest("You are not friends");
            return;
        }

        $stmt = $conn->prepare("DELETE FROM Freunde WHERE freunde_userid=? AND freunde_freundid=?");
        $stmt->bind_param("ii", $userid, $fid);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM Freunde WHERE freunde_userid=? AND freunde_freundid=?");
        $stmt->bind_param("ii", $fid, $userid);
        $stmt->execute();
        $stmt->close();

        Response::ok("Friend removed");
    }

    public static function deletePrivatethread(mysqli $conn, User $user) {
        $username = $user->getName();
        $stmt = $conn->prepare("DELETE FROM Posts WHERE posts_aim=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();

        Response::ok("Your private thread has been deleted");
    }

    public static function deletePublic(mysqli $conn, User $user, int $pid) {
        $stmt = $conn->prepare("SELECT posts_user FROM Posts WHERE posts_id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        $stmt->close();

        if($user->getName() != $name && $user->isOp() != 1 && $user->isAdmin() != 1) {
            Response::forbidden("Unauthorized");
            return;
        }

        $stmt = $conn->prepare("DELETE FROM Posts WHERE posts_id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->close();

        Response::ok("Post deleted");
    }

    public static function deletePrivate(mysqli $conn, User $user, int $pid) {
        $stmt = $conn->prepare("SELECT posts_user, posts_aim FROM Posts WHERE posts_id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->bind_result($name, $aim);
        $stmt->fetch();
        $stmt->close();

        if($user->getName() != $name && $user->getName() != $aim && $user->isOp() != 1 && $user->isAdmin() != 1) {
            Response::forbidden("Unauthorized");
            return;
        }

        $stmt = $conn->prepare("SELECT posts_bild FROM Posts WHERE posts_id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->bind_result($img);
        $stmt->fetch();
        $stmt->close();

        if($img !== NULL)
            unlink(ROOT_DIR . $img);

        $stmt = $conn->prepare("DELETE FROM Posts WHERE posts_id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->close();

        Response::ok("Post deleted");
    }

    public static function deleteShare(mysqli $conn, User $user, int $pid) {
        $username = $user->getName();

        $stmt = $conn->prepare("SELECT userposts_id FROM Userposts WHERE userposts_name=? AND userposts_sharedid=?");
        $stmt->bind_param("si", $username, $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0) {
            Response::notfound("No such post");
            return;
        }

        list($id) = $result->fetch_array();

        $stmt = $conn->prepare("DELETE FROM Userposts WHERE userposts_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        Response::ok("Post is not been shared anymore");
    }

    public static function deleteComment(mysqli $conn, User $user, int $pid) {
        $stmt = $conn->prepare("SELECT userposts_name FROM Userposts WHERE userposts_id=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0) {
            Response::notfound("No such post");
            return;
        }

        list($name) = $result->fetch_array();

        if($name != $user->getName() && $user->isOp() != 1 && $user->isAdmin() != 1) {
            Response::forbidden("Unauthorized");
            return;
        }

        $stmt = $conn->prepare("DELETE FROM Userposts WHERE userposts_id=? OR userposts_sharedid=?");
        $stmt->bind_param("ii", $pid, $pid);
        $stmt->execute();
        $stmt->close();

        Response::ok("Post vollständig gelöscht");
    }

    public static function deleteReportedComment(mysqli $conn, User $user, int $pid) {
        if($user->isOp() != 1 && $user->isAdmin() != 1) {
            Response::forbidden("Unautorized");
            return;
        }

        $stmt = $conn->prepare("DELETE FROM Report WHERE report_postid=?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $stmt->close();

        Response::ok("Report removed");
    }

    public static function deleteAnswer(mysqli $conn, User $user, int $aid) {
        $stmt = $conn->prepare("SELECT useranswers_postid, userposts_name FROM Useranswers INNER JOIN Userposts ON useranswers_id=? AND useranswers_postid=userposts_id");
        $stmt->bind_param("i", $aid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows <= 0) {
            Response::notfound("No such answer");
            return;
        }

        list($postid, $name) = $result->fetch_array();

        if($name !== $user->getName() && $user->isAdmin() != 1) {
            Response::forbidden("Unauthorized");
            return;
        }

        $stmt = $conn->prepare("DELETE FROM Useranswers WHERE useranswers_id=? AND useranswers_postid=?");
        $stmt->bind_param("ii", $aid, $postid);
        $stmt->execute();
        $stmt->close();

        Response::ok("Answer deleted");
    }

    public static function deleteAccount(mysqli $conn, User $user) {
        $userid = $user->getId();
        $stmt = $conn->prepare("DELETE FROM Users WHERE users_id=?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $stmt->close();

        session_destroy();
    }
}
?>