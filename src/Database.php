<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 2:00 PM
 */

namespace KeywordRouter;


class Database
{
    /**
     * @var \mysqli
     */
    private $conn;

    private static $_instance; //The single instance

    /*
	 * Get an instance of the Database
	 * @return Instance
	 */
    public static function getInstance() {

        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct()
    {

        $config = \KeywordRouter\Helper::getConfig();

        $database = $config->database();

        $this->conn = new \mysqli($database['host'], $database['user'], $database['pass'], $database['name']);

        if (mysqli_connect_errno()) {
            die(sprintf("Connect failed: %s\n", mysqli_connect_error()));
        }
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }


    public function connection() {
        return $this->conn;
    }

}