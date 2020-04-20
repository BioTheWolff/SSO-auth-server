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
    
    public static function getInstance(): \PDO {
        if(!self::$database){
            self::$database = new \PDO('pgsql:host=' . DATABASE_HOSTNAME . ';dbname=' . DATABASE_DATABASE, DATABASE_USERNAME, DATABASE_PASSWORD, [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
        }
        
        return self::$database;
    }

    private static function getServers(array $servers, int $user_id): array {
        $db = self::getInstance();
        $arr = null;
        foreach ($servers as $guild) {
            $id = $guild->id;
            $q = $db->prepare("SELECT * FROM servers WHERE server_id = ?");
            $q->execute([$id]);
            $end = $q->fetch();
            if ($end !== false and $end->commander_role_id) {
                $own_id = ($end->owner_id == $user_id) ? true : false;
                $arr[] = ['id' => $end->server_id, 'name' => $end->server_name, 'img' => $end->server_image, 'owner' => $own_id];
            }
        }
        return $arr;
    }
}