<?php
namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
class JWTToken {
    
    public static function CreateToken($userEmail) {
        $key = env('JWT_KEY');
        
        $payload = [
            'iss' => 'laravel-jwt',
            'iat' => time(),
            'exp' => time() + 60*60,
            'user' => $userEmail
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function CreateTokenSetPassword($userEmail) {
        $key = env('JWT_KEY');
        
        $payload = [
            'iss' => 'laravel-jwt',
            'iat' => time(),
            'exp' => time() + 60*1,
            'user' => $userEmail
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function DecodeToken($token) {
        try {
            $key = env('JWT_KEY');
            $decode = JWT::decode($token, new Key($key, 'HS256'));   
            return $decode->user;
        } catch (Exception $e) {
            return "unauthorized";
        }
    }
}
