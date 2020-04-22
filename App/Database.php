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
            self::$database = new \PDO('pgsql:host=' . DATABASE_HOSTNAME . ';dbname=' . DATABASE_DATABASE, DATABASE_USERNAME, DATABASE_PASSWORD, [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
        }
        
        return self::$database;
    }

    /**
     * GET USER
     */
    public static function getUserWithEmail(String $email) {
        $db = self::getInstance();

        $email = \htmlspecialchars($email);

        $q = $db->prepare('SELECT * FROM accounts WHERE email = ?');
        $q->execute([$email]);
        return $q->fetch();
    }

    public static function getUserWithUsername(String $username) {
        $db = self::getInstance();

        $username = \htmlspecialchars($username);

        $q = $db->prepare('SELECT * FROM accounts WHERE username = ?');
        $q->execute([$username]);
        return $q->fetch();
    }

    public static function getUserFromFull(Int $id, String $email, String $username) {
        $db = self::getInstance();

        $email = \htmlspecialchars($email);
        $username = \htmlspecialchars($username);

        $q = $db->prepare('SELECT * FROM accounts WHERE id = ? AND email = ? AND username = ?');
        $q->execute([$id, $email, $username]);
        return $q->fetch();
    }

    /**
     * UPDATE USER
     */
    public static function updateUserProfile($id, $new_email, $new_username): bool {
        $db = self::getInstance();

        $email = \htmlspecialchars($new_email);
        $username = \htmlspecialchars($new_username);

        $q = $db->prepare('UPDATE accounts SET email = ?, username = ? WHERE id = ?');
        $q->execute([
            $email, 
            $username,
            $id
            ]);
        return $q->rowCount() > 0;
    }
}