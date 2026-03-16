<?php
    class AES {
        static function encrypt($plaintext, $password) {
            $method = "aes-256-cbc";

            $key = substr(hash("sha256", $password, true), 0, 32);

            $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

            $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv));

            return $encrypted;
        }

        static function decrypt($encryptedText, $password) {
            $method = "aes-256-cbc";

            $key = substr(hash("sha256", $password, true), 0, 32);

            $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

            $decrypted = openssl_decrypt(base64_decode($encryptedText), $method, $key, OPENSSL_RAW_DATA, $iv);

            return $decrypted;
        }
    }
?>