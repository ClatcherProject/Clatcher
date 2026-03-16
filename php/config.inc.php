<?php
    session_set_cookie_params([
	    "lifetime" => 0,
	    "path" => "/",
	    "domain" => $_SERVER["SERVER_NAME"],
	    "secure" => TRUE,
	    "httponly" => TRUE,
	    "samesite" => "lax"
    ]);

    define('HOST', '<Your Database server>');
    define('DATABASE', '<Your Database>');
    define('USER', '<Your Database user');
    define('PASSWORD', '<Your Database password>');
    define('INIT_QUERY', 'SET NAMES UTF8');

    # Root-Directory
    define("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);

    // If you change one of this following constants, make sure you also change the constants in the following files:
    //

    // Min username length
    define('MIN_USERNAME_LENGTH', 4);

    // Max username length
    define('MAX_USERNAME_LENGTH', 30);

    // Max characters a user can post from a textfield
    define('MAX_CHARACTERS', 2000);

    // Max filesize a user can post as a comment. value in bytes. shown as mb in web
    define('MAX_POST_FILESIZE', 5242880);

    // Max filesize a user can upload a logo. value in bytes. shown as mb in web
    define('MAX_LOGO_FILESIZE', 5242880);

    // Max filesize a user can upload a background wallpaper. value in bytes. shown as mb in web
    define('MAX_BACKGROUND_FILESIZE', 5242880);

    // Max filesize a user can upload a header. value in bytes. shown as mb in web
    define('MAX_HEADER_FILESIZE', 5242880);

    // Max filesize a user can upload an eventimage on his public site. value in bytes. shown as mb in web
    define('MAX_EVENTIMAGE_FILESIZE', 5242880);

    // Max filesize a user can upload a video on his public site. value in bytes. shown as mb in web
    define('MAX_VIDEO_FILESIZE', 41943040);

    // Max video durattion a user can upload on his public site. value in seconds. shown as minutes in web
    define('MAX_VIDEO_DURATION', 180);

    // Max image filesize a user can upload on his public site. value in bytes. shown as mb in web
    define('MAX_PUBLIC_IMAGE_FILESIZE', 5242880);

    // Max filesize a user can upload into his personal storage. value in bytes. shown as mb in web
    define('MAX_STORAGE_FILESIZE', 41943040);

    require_once("classes/AES.php");
    require_once("classes/User.php");
    require_once("classes/getid3/getid3.php");
    require_once("Response.php");
    require_once("Router.php");
    require_once("sites/Index.php");
    require_once("sites/Blogs.php");
    require_once("sites/Developers.php");
    require_once("sites/Usersite.php");
    require_once("sites/Privatespace.php");
    require_once("sites/ShareFile.php");
    require_once("sites/Report.php");
    require_once("sites/PasswordChanger.php");
    require_once("Utility.php");
    require_once("Emoji.php");
    require_once("GET.php");
    require_once("POST.php");
    require_once("PUT.php");
    require_once("DELETE.php");
?>
