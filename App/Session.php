<?php

namespace App;

class Session {
    
    const FLASH_TYPE_SUCCESS = 'is-success';
    const FLASH_TYPE_ERROR   = 'is-danger';
    const FLASH_TYPE_INFO    = 'is-info';
            
    
    public static function init(){
        session_start();

        if(isset($_SESSION['__flash'])){
            $_SESSION['__flash']['__count']--;
            if($_SESSION['__flash']['__count'] == 0){
                unset($_SESSION['__flash']);
            }
        }
    }

    public static function flash(string $key, $value){
        if(!isset($_SESSION['__flash'])){
            $_SESSION['__flash'] = ['__count' => 2];
        }

        $_SESSION['__flash'][$key] = $value;
    }

    public static function get_flash(string $key, $default = null){
        $data = $_SESSION['__flash'] ?? [];
        return $data[$key] ?? $default;
    }

    public static function has_flash(string $key): bool {
        return isset($_SESSION['__flash']) && isset($_SESSION['__flash'][$key]);
    } 
    
    public static function flash_message(string $type, string $message){
        self::flash('__message', ['type' => $type, 'message' => $message]);
    }
    
    public static function is_connected(): bool {
        return isset($_SESSION['__user']);
    }
}

