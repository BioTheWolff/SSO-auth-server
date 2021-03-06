<?php

namespace App;

/**
 * Class Session
 * @package App
 * @author l4p1n
 */
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
    
    // SESSION USER
    public static function is_connected(): bool {
        return isset($_SESSION['__user']);
    }

    public static function get_user_value($key) {
        if (!self::is_connected()) return null;

        return isset($_SESSION['__user'][$key]) ? $_SESSION['__user'][$key] : null;
    }

    public static function is_user_admin(): bool {
        if (!self::is_connected()) return false;

        return self::get_user_value('admin');
    }

    public static function populate_user_session($id, $username, $email, $admin = false) {
        $_SESSION['__user'] = array(
            'id' => $id,
            'email' => $email,
            'username' => $username,
            'admin' => $admin
        );
    }

    public static function db_result_for_user_details() {
        if (!self::is_connected()) return null;

        return Database::getUserFromFull(
            self::get_user_value('id'),
            self::get_user_value('email'),
            self::get_user_value('username')
        );
    }

    public static function disconnect() {
        unset($_SESSION['__user']);
    }
}

