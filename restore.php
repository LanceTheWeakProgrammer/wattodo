<?php
require_once 'core/Database.php';

$storageDir = __DIR__ . '/storage';

$backupFiles = array_diff(scandir($storageDir), array('.', '..', '.gitkeep'));

if (empty($backupFiles)) {
    die("No backup files found in the storage directory.\n");
}

echo "Available backup files:\n";
foreach ($backupFiles as $index => $file) {
    echo "[$index] $file\n";
}

echo "Enter the number of the backup file to restore (or enter / to cancel): ";
$fileIndex = trim(fgets(STDIN));

if ($fileIndex === '/') {
    die("Operation cancelled by user.\n");
}

if (!isset($backupFiles[$fileIndex])) {
    die("Invalid selection. Exiting...\n");
}

$backupFilePath = $storageDir . '/' . $backupFiles[$fileIndex];

echo "Would you like to:\n";
echo "[1] Restore to a new database\n";
echo "[2] Restore to an existing database\n";
echo "[/] Cancel\n";
echo "Enter your choice: ";
$choice = trim(fgets(STDIN));

if ($choice === '/') {
    die("Operation cancelled by user.\n");
}

if ($choice === '1') {
    echo "Enter the new database name to restore to: ";
    $newDbName = trim(fgets(STDIN));

    $db = new Database();
    $db->restoreDatabase($backupFilePath, $newDbName);

} elseif ($choice === '2') {
    echo "Enter the name of the existing database to restore to: ";
    $existingDbName = trim(fgets(STDIN));

    echo "Restoring to the existing database: $existingDbName\n";

    $db = new Database();
    try {
        $db->restoreDatabase($backupFilePath, $existingDbName);
        echo "Database restoration to `$existingDbName` completed successfully.\n";
    } catch (Exception $e) {
        echo "Error: Unable to restore to the existing database. " . $e->getMessage() . "\n";
    }

} else {
    echo "Invalid choice. Exiting...\n";
}
