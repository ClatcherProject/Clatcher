<?php
    class User {
        private $id;
        private $name;
        private $pass;
        private $header;
        private $logo;
        private $background;
        private $mail;
        private $activated;
        private $activatecode;
        private $op;
        private $admin;
        private $banned;
        private $date;

        public function __construct($id, $name, $pass, $header, $logo, $background, $mail, $activated, $activatecode, $op, $admin, $banned, $date) {
            $this->id = $id;
            $this->name = $name;
            $this->pass = $pass;
            $this->header = $header;
            $this->logo = $logo;
            $this->background = $background;
            $this->mail = $mail;
            $this->activated = $activated;
            $this->activatecode = $activatecode;
            $this->op = $op;
            $this->admin = $admin;
            $this->banned = $banned;
            $this->date = $date;
        }

        public function __destruct() {
            unset($this->conn);
        }

        public static function makeUser(mysqli $conn, string $name) {

            $stmt = $conn->prepare("SELECT users_id, users_pass, users_header, users_logo, users_background, users_mail, users_activated, users_activatecode, users_op, users_admin, users_banned, users_date FROM Users WHERE users_name=?");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if($result->num_rows > 0) {
                list($id, $pass, $header, $logo, $background, $mail, $activated, $activatecode, $op, $admin, $banned, $date) = mysqli_fetch_array($result);

                $user = new User($id, $name, $pass, $header, $logo, $background, $mail, $activated, $activatecode, $op, $admin, $banned, $date);
            }
            else {
                $user = new User(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
            }

            return $user;
        }

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function setPass(string $pass) {
            $this->pass = $pass;
        }

        public function getPass() {
            return $this->pass;
        }

        public function setHeader(string $header) {
            $this->header = $header;
        }

        public function getHeader() {
            return $this->header;
        }

        public function setLogo(string $logo) {
            $this->logo = $logo;
        }

        public function getLogo() {
            return $this->logo;
        }

        public function setBackground(string $background) {
            $this->background = $background;
        }

        public function getBackground() {
            return $this->background;
        }

        public function getMail() {
            return $this->mail;
        }

        public function setActivated(int $activate) {
            $this->activated = $activate;
        }

        public function isActivated() {
            return $this->activated;
        }

        public function setActivatecode(string $code) {
            $this->activatecode = $code;
        }

        public function getActivatecode() {
            return $this->activatecode;
        }

        public function isOp() {
            return $this->op;
        }

        public function isAdmin() {
            return $this->admin;
        }

        public function isBanned() {
            return $this->banned;
        }

        public function getDate() {
            return $this->date;
        }

        public function hasUsersite(mysqli $conn) {
            $sql = "SELECT usersite_name FROM Usersite WHERE usersite_name=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $this->name);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if($result->num_rows == 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
    }
?>