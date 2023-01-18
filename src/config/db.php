<?php

$dotenv = Dotenv\Dotenv::createImmutable ("/Users/kullop/Desktop/study/slim-rest-api/");
$dotenv->load();

class DB {
    private $host;
    private $user;
    private $password;
    private $dbname;

    public function __construct(){
        $this->host = $_ENV["HOST"];
        $this->password = $_ENV["DB_PASSWORD"];
        $this->dbname = $_ENV["DB_NAME"];
        $this->user = $_ENV["DB_USER"];
    }
    public function connect(){
        $dsn = "mysql:host=$this->host;dbname=$this->dbname";
        $conn = new PDO($dsn, $this->user, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}