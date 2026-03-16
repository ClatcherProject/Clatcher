<?php
class Utility {
    private static $types = array(
        "mp3" => "audio/mpeg",
        "ogg" => "audio/ogg",
        "opus" => "audio/ogg",
        "mp4" => "video/mp4",
        "webm" => "video/webm",
        "png" => "image/png",
        "jpg" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "gif" => "image/gif",
        "txt" => "text/plain",
        "jar" => "application/java-archive",
        "epub" => "application/epub+zip",
        "pdf" => "application/pdf",
        "json" => "application/json",
        "xml" => "text/xml",
        "apk" => "application/vnd.android.package-archive",
        "html" => "text/html"
    );

    private static $exts = array(
        IMAGETYPE_JPEG => ".jpeg",
        IMAGETYPE_PNG => ".png",
        IMAGETYPE_GIF => ".gif"
    );

    public static function contentType(string $ext) {
        return self::$types[$ext];
    }

    public static function ext(int $ext) {
        return self::$exts[$ext];
    }

    public static function isValidPic(int $ext) {
        return array_key_exists($ext, self::$exts);
    }
}
?>