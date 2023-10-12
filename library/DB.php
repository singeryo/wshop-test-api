<?php

/**
 * @desc Database class - used to initiate the database connection
 * @author Paul Doelle
 */
class DB
{
    const CONFIG_FILE = 'database.json';

    private static $instance = null;

    /**
     * @return PDO
     * @throws Exception
     */
    public static function get(): PDO
    {
        if (self::$instance == null) {
            if (!$config = file_get_contents(self::CONFIG_FILE)) {
                throw new DatabaseConnectionException('Database config file not found', 500);
            }

            $configJson = json_decode($config, true);

            try {
                self::$instance = new PDO(
                    "mysql:host={$configJson['db_host']};dbname={$configJson['db_name']}",
                    $configJson['db_user'],
                    $configJson['db_pass']
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new DatabaseConnectionException("Unable to connect to the database - " . $e->getMessage(), 500);
            }
        }

        return self::$instance;
    }

    /**
     * @return mixed
     */
    public static function lastInsertId()
    {
        return self::$instance->lastInsertId();
    }
}
