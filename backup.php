<?php
require_once 'core/Database.php'; 

$db = new Database(); 

$backupFilePath = __DIR__ . '/storage/backup_' . date('Y-m-d_H-i-s') . '.sql';

$db->backupDatabase($backupFilePath);

echo "Backup process completed.\n";
