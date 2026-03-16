<?php
class Response {
    private static function response(int $code, string $status, mixed $info) {
        $response = array(
            "code" => $code,
            "status" => $status,
            "info" => $info
        );

        $response = json_encode($response);
        header("HTTP/1.1 $code $status");
        header("Content-Type: application/json");
        echo($response);
    }

    public static function ok($info) {
        self::response(200, "OK", $info);
    }

    public static function badRequest($info) {
        self::response(400, "Bad Request", $info);
    }

    public static function forbidden($info) {
        self::response(401, "Forbidden", $info);
    }

    public static function notfound($info) {
        self::response(404, "Not Found", $info);
    }

    public static function toomanyrequests($info) {
        self::response(429, "Too Many Requests", $info);
    }

    public static function internalservererror($info) {
        self::response(500, "Internal Server Error", $info);
    }
}
?>