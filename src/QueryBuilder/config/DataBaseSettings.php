<?php
namespace QueryBuilder\config;
/**
 * @summary: implementacao do padrao singleton para lidar com apenas uma instancia desta classe para obter os mesmos dados do array de configuracoes do banco de dados e persistir mudanças
 * @author : Lucas Felipe Lima Cid <lucasfelipaaa@gmail.com>
 * @created : 08/06/2024
 */
class DataBaseSettings 
{
    private static $instance = null;
    private $dataBaseConfig;
    private function __construct () 
    {
        include "DataSource.php";
        
        $this->dataBaseConfig = $config;
    }

    public static function getInstance() 
    {
        if(self::$instance == null) 
        {
            self::$instance = new self;
            return self::$instance;
        }
        return self::$instance;
    }

    /**
     * $summary : Alterar valor de alguma propriedade dentro do array associativo de configuracoes do banco de dados
     * @author Lucas Cid <lucasfelipaaa@gmail.com>
     * @param "database"|"username"|"password"|"dbName"|"host"|"isAdiantiFrameworkProject" $key : Chave correspondente a qual 
     * queres invocar para substituir pelo proximo parametro
     * @param string|int|bool $value : valor a ser substituido no chaveamento do array
     */
    public function setDatabaseConfigByKey(string $key, string|int|bool $value) 
    {
        $this->dataBaseConfig[$key] = $value;
    }
    public function getDataFromArrayByKey(string $key) 
    {
        return $this->dataBaseConfig[$key];
    }
    public function getSettings () 
    {
        return $this->dataBaseConfig;
    }
    private function __clone() {}
}
