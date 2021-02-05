<?php
namespace Database;

class DB
{
    private static $_instance = null;
    private $db;
    // для безопасности настройки лучше хранить в файле с конфигом
    private const DB_HOST = 'mariadb';
	private const DB_NAME = 'sionik';
	private const DB_USER = 'root';
	private const DB_PASS = 'rootpwd6421';

	public function __construct () {

        try {
            $this->db = new \PDO(
                'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME,
                self::DB_USER,
                self::DB_PASS
            );
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->exec("set names utf8");
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }


    }

	private function __clone () {}
	private function __wakeup () {}

    public function getDb() {
        if ($this->db instanceof \PDO) {
            return $this->db;
        }
    }
}