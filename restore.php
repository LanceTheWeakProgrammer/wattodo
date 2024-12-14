<?php
require_once 'core/Database.php';

// List available backup files in the storage directory
$storageDir = __DIR__ . '/storage';
$backupFiles = array_diff(scandir($storageDir), array('.', '..'));

if (empty($backupFiles)) {
    die("No backup files found in the storage directory.\n");
}

echo "Available backup files:\n";
foreach ($backupFiles as $index => $file) {
    echo "[$index] $file\n";
}

// Prompt the user to select a backup file
echo "Enter the number of the backup file to restore: ";
$fileIndex = trim(fgets(STDIN));

if (!isset($backupFiles[$fileIndex])) {
    die("Invalid selection. Exiting...\n");
}

$backupFilePath = $storageDir . '/' . $backupFiles[$fileIndex];

// Prompt the user for the database name to restore to
echo "Enter the database name to restore to: ";
$newDbName = trim(fgets(STDIN));

// Create a Database instance and restore the backup
$db = new Database();
$db->restoreDatabase($backupFilePath, $newDbName);
