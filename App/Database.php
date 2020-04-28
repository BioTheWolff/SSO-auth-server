<?php

namespace App;

/**
 * Classe d'accès à la base de données
 *
 * @author l4p1n
 */
class Database {
    /**
     * Connexion à la base de données
     * @var \PDO
     */
    protected static $database = null;
    
    private static function getInstance(): \PDO {
        if(!self::$database){
            self::$database = new \PDO(DATABASE_SYSTEM . ':host=' . DATABASE_HOSTNAME . ';dbname=' . DATABASE_DATABASE, DATABASE_USERNAME, DATABASE_PASSWORD, [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
        }
        
        return self::$database;
    }

    /**
     * GET ALL USERS
     */
    public static function getAllUsersInfo() {
        $db = self::getInstance();

        $q = $db->query('SELECT username, email FROM ' . DATABASE_TABLE);
        return $q->fetchAll();
    }

    /**
     * GET USER
     */
    public static function getUserWithEmail(String $email) {
        $db = self::getInstance();

        $email = e($email);

        $q = $db->prepare('SELECT * FROM ' . DATABASE_TABLE . ' WHERE email = ?');
        $q->execute([$email]);
        return $q->fetch();
    }

    public static function getUserWithUsername(String $username) {
        $db = self::getInstance();

        $username = e($username);

        $q = $db->prepare('SELECT * FROM ' . DATABASE_TABLE . ' WHERE username = ?');
        $q->execute([$username]);
        return $q->fetch();
    }

    public static function getUserWithEither(String $eval) {
        $db = self::getInstance();

        $eval = e($eval);

        $q = $db->prepare('SELECT * FROM ' . DATABASE_TABLE . ' WHERE username = ? OR email = ?');
        $q->execute([$eval, $eval]);
        return $q->fetch();
    }

    public static function getUserFromFull(Int $id, String $email, String $username) {
        $db = self::getInstance();

        $email = e($email);
        $username = e($username);

        $q = $db->prepare('SELECT * FROM ' . DATABASE_TABLE . ' WHERE id = ? AND email = ? AND username = ?');
        $q->execute([$id, $email, $username]);
        return $q->fetch();
    }

    /**
     * UPDATE USER
     */
    public static function updateUserProfile($id, $new_email, $new_username): bool {
        $db = self::getInstance();

        $email = e($new_email);
        $username = e($new_username);

        $q = $db->prepare('UPDATE ' . DATABASE_TABLE . ' SET email = ?, username = ? WHERE id = ?');
        $q->execute([
            $email, 
            $username,
            $id
            ]);
        return $q->rowCount() > 0;
    }

    public static function updateUserPassword(int $id, String $password): bool {
        $db = self::getInstance();

        $hash = \password_hash($password, PASSWORD_DEFAULT);

        $q = $db->prepare('UPDATE ' . DATABASE_TABLE . ' SET password = ? WHERE id = ?');
        $q->execute([
            $hash,
            $id
            ]);
        return $q->rowCount() > 0;
    }

    /**
     * INSERT USER
     */
    public static function createUser($email, $username, $password): bool {
        $db = self::getInstance();

        $email = e($email);
        $username = e($username);
        $hash = \password_hash($password, PASSWORD_DEFAULT);

        $q = $db->prepare('INSERT INTO ' . DATABASE_TABLE . ' (email, username, password) VALUES (?, ?, ?)');
        $q->execute([
            $email,
            $username,
            $hash
        ]);
        return $q->rowCount() > 0;
    }
}