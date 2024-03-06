<?php
namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use PhpParser\Node\Expr\Cast\Object_;
use PhpParser\Node\Expr\Cast\String_;

class JWTToken {
    
    public static function CreateToken($userEmail, $userId) {
        $key = env('JWT_KEY');
        
        $payload = [
            'iss' => 'laravel-jwt',
            'iat' => time(),
            'exp' => time() + 60*60,
            'user' => $userEmail,
            'userId' => $userId,
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function CreateTokenSetPassword($userEmail) {
        $key = env('JWT_KEY');
        
        $payload = [
            'iss' => 'laravel-jwt',
            'iat' => time(),
            'exp' => time() + 60*1,
            'user' => $userEmail,
            'userId' => '0',
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function DecodeToken($token):Object|String {
        if(null==$token) {
            return 'unauthorized';
        } else {
            try {
                $key = env('JWT_KEY');
                $decode = JWT::decode($token, new Key($key, 'HS256'));   
                return $decode;
            } catch (Exception $e) {
                return "unauthorized";
            }
        }
    }
}
