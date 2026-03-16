<?php
class ShareFile {
    public static function load(mysqli $conn, User $user, string $name, int $friendid) {
        ?>
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <title>Clatcher - Share Your File</title>
                <meta charset="utf8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <script src="/scripts/angular.min.js"></script>
                <link rel="icon" type="image/x-icon" href="/icon.ico">
                <link rel="stylesheet" href="/styles/index.css">
            </head>
            <body class="bg-darkblue text-light">
                <div class="padding-middle">
                    <div id="info"></div>
                    <p>This feature is currently under construction</p>
                </div>
            </body>
        </html>
        <?php
    }
}
?>