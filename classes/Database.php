<?php

class Database
{
    private static $instance = null;
    private $db;

    private function __construct()
    {
        $config = require './config/db.php';

        try {
            $this->db = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']}", $config['db_user'], $config['db_pass']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }

    public static function init() :object {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getDb()
    {
        return $this->db;
    }



}