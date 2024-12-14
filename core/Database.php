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
    
            // Disable foreign key checks for easier restore
            $sqlDump = "SET FOREIGN_KEY_CHECKS = 0;\n\n";
    
            // Get all tables
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
            // Process each table
            foreach ($tables as $table) {
                // Get the CREATE TABLE statement
                $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC)['Create Table'];
                $sqlDump .= "$createTable;\n\n";
    
                // Get the table data
                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    
                // Create INSERT statements for each row
                foreach ($rows as $row) {
                    $values = array_map(function ($value) use ($pdo) {
                        return is_null($value) ? 'NULL' : $pdo->quote($value);
                    }, $row);
                    $sqlDump .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
                }
    
                $sqlDump .= "\n\n";
            }
    
            // Re-enable foreign key checks
            $sqlDump .= "SET FOREIGN_KEY_CHECKS = 1;\n";
    
            // Write the dump to a file
            file_put_contents($backupFilePath, $sqlDump);
    
            echo "Database backup created successfully at: $backupFilePath\n";
    
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "\n";
        }
    }

    public function restoreDatabase($backupFilePath, $newDbName) {
        try {
            // Connect to MySQL without a database to create the new one
            $pdo = new PDO("mysql:host=$this->host", $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create the new database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$newDbName`");
            echo "Database `$newDbName` created successfully.\n";

            // Connect to the new database
            $pdo = $this->connect($newDbName);

            // Load the SQL dump and execute it
            $sql = file_get_contents($backupFilePath);
            $pdo->exec($sql);

            echo "Database restored successfully from: $backupFilePath\n";

        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . "\n";
        }
    }
}
