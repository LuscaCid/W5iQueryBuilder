<?php
namespace QueryBuilder\config;

use PDO;
use PDOException;

class DbConnection 
{
    public PDO $PDO;

    public function __construct() 
    {
        $databaseSettings = DataBaseSettings::getInstance();
        $config = $databaseSettings->getSettings();

        $username = $config["username"];
        $password = $config["password"];
        $database = $config["database"];
        $dbName   = $config["dbName"];
        $host     = $config["host"];
        
        try {

            $this->PDO = new PDO("$database:host={$host};dbname={$dbName}", $username, $password);
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            die("Erro na conexão com o banco de dados: " . $e->getMessage());

        }
    }
} 