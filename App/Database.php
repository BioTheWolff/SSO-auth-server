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
     * GET ALL USERS
     */
    public static function getAllUsersInfo() {
        $db = self::getInstance();

        $q = $db->query('SELECT username, email FROM accounts');
        return $q->fetchAll();
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

    public static function getUserWithEither(String $eval) {
        $db = self::getInstance();

        $eval = \htmlspecialchars($eval);

        $q = $db->prepare('SELECT * FROM accounts WHERE username = ? OR email = ?');
        $q->execute([$eval, $eval]);
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

    public static function updateUserPassword(int $id, String $password): bool {
        $db = self::getInstance();

        $hash = \password_hash($password, PASSWORD_DEFAULT);

        $q = $db->prepare('UPDATE accounts SET password = ? WHERE id = ?');
        $q->execute([
            $hash,
            $id
            ]);
        return $q->rowCount() > 0;
    }
}