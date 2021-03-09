<?php

namespace Storage;

use PDO;
use PDOStatement;

class DbConnection extends AbstractStorage
{
    /**
     * @param array $config
     * @return AbstractStorage
     * @throws \Exception
     */
    public static function getInstance(array $config): AbstractStorage
    {
        if (self::$instance != null) {
            return self::$instance;
        }
        
        return new self($config);
    }
    
    /**
     * DbConnection constructor.
     * @param array $config
     * @throws \Exception
     */
    private function __construct(array $config)
    {
        if (
            empty($config['host']) || empty($config['db_name'])
            || empty($config['user']) || empty($config['password'])
        ) {
            throw new \Exception('Incorrect parameters');
        }
        self::$instance = new PDO(
            'mysql:host=' . $config['host'] . ';dbname=' . $config['db_name'],
            $config['user'],
            $config['password'],
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
        );
    }
    
    /**
     * @param string $table_name
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function saveData(string $table_name, array $data): bool
    {
        if (!$table_name || !$data) {
            throw new \Exception('Incorrect parameters');
        }

        $column_names = array_keys($data);
        $column_list = implode(', ', $column_names);
        $placeholder_list = ':' . implode(', :', $column_names);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $table_name, $column_list, $placeholder_list);
        return self::$instance->prepare($sql)->execute($data);
    }
    
    /**
     * @param string $table_name
     */
    public function removeAll(string $table_name): void
    {
        self::$instance->exec('DELETE FROM ' . $table_name);
    }
    
    /**
     * @param $sql
     * @return PDOStatement
     */
    public function query($sql): PDOStatement
    {
        return self::$instance->query($sql);
    }
    
    protected function __clone()
    {
        return false;
    }
}
