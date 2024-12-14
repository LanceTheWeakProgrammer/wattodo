<?php
class Database {

    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $db_name = 'edm_database';

    public function connect($dbName = null) {
        try {
            $dbName = $dbName ?? $this->db_name;
            $pdo = new PDO("mysql:host=$this->host;dbname=$dbName", $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public function backupDatabase($backupFilePath) {
        try {
            $pdo = $this->connect();

            $sqlDump = "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC)['Create Table'];
                $sqlDump .= "$createTable;\n\n";

                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $values = array_map(function ($value) use ($pdo) {
                        return is_null($value) ? 'NULL' : $pdo->quote($value);
                    }, $row);
                    $sqlDump .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
                }
    
                $sqlDump .= "\n\n";
            }

            $sqlDump .= "SET FOREIGN_KEY_CHECKS = 1;\n";

            file_put_contents($backupFilePath, $sqlDump);
    
            echo "Database backup created successfully at: $backupFilePath\n";
    
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "\n";
        }
    }

    public function restoreDatabase($backupFilePath, $newDbName) {
        try {
            $pdo = new PDO("mysql:host=$this->host", $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$newDbName`");
            echo "Database `$newDbName` created successfully.\n";

            $pdo = $this->connect($newDbName);

            $sql = file_get_contents($backupFilePath);
            $pdo->exec($sql);

            echo "Database restored successfully from: $backupFilePath\n";

        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "\n";
        }
    }
}
