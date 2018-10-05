<?php

class TokenEngine
{

    private static $secret = "L1Br0S3cr3t";

    public static function makeToken($payload)
    {
        $head = json_encode([
            "typ" => "JWT",
            "alg" => "HS256"
        ]);
        $body = json_encode($payload);
        $head64 = self::base64URLEncode($head);
        $body64 = self::base64URLEncode($body);
        $sig = hash_hmac('sha256', $head64 . "." . $body64, self::$secret);
        $sig64 = self::base64URLEncode($sig);
        $jwt = $head64 . "." . $body64 . "." . $sig64;
        return $jwt;
    }

    public static function readToken($tok)
    {
        $parts = explode(".", $tok);
        if (count($parts) != 3) {
            return false;
        }
        else {
            $decoded = ["head"=>base64_decode($parts[0]), "body"=>base64_decode($parts[1]), "signature"=>base64_decode($parts[2])];
            $expected = hash_hmac('sha256', $parts[0] . "." . $parts[1], self::$secret);
            if(hash_equals($decoded["signature"], $expected)){
                return json_decode($decoded["body"]);
            } else {
                return false;
            }
        }
    }

    private static function base64URLEncode($line)
    {
        return str_replace([
            '+',
            '/',
            '='
        ], [
            '-',
            '_',
            ''
        ], base64_encode($line));
    }
}