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

    public static function getUserWithEmail(String $email) {
        $db = self::getInstance();

        $q = $db->prepare('SELECT * FROM accounts WHERE email = ?');
        $q->execute([$email]);
        return $q->fetch();
    }
}