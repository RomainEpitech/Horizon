<?php

    require './vendor/autoload.php';
    use Horizon\Core\LogHandler;

    $log = new LogHandler();

    if ($argc !== 7) {
        echo "Usage: php create_database.php <dbName> <dbUser> <dbPass> <dbHost> <dbPort> <dbChar>\n";
        exit(1);
    }

    $dbName = $argv[1];
    $dbUser = $argv[2];
    $dbPass = $argv[3];
    $dbHost = $argv[4];
    $dbPort = $argv[5];
    $dbChar = $argv[6];

    function generateToken() {
        return bin2hex(random_bytes(16));
    }

    try {
        if ($dbHost === 'localhost') {
            $dsn = "mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock";
        } else {
            $dsn = "mysql:host={$dbHost};port={$dbPort}";
        }

        $pdo = new PDO($dsn, $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "CREATE DATABASE IF NOT EXISTS `$dbName`";
        $pdo->exec($sql);

        echo "Database `$dbName` created successfully.\n";
        $pdo->exec("USE `$dbName`");

        $sql = "
            CREATE TABLE IF NOT EXISTS `migrations` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `migration` VARCHAR(255) NOT NULL,
                `executed_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
        $pdo->exec($sql);

        $sql = "
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `email` VARCHAR(255) NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `token` VARCHAR(255),
                `role` JSON
            )";
        $pdo->exec($sql);

        $log->log("New database `$dbName` created");
        $log->log("New table `migrations` created");
        $log->log("New table `users` created");
    } catch (PDOException $e) {
        echo "Database creation failed: " . $e->getMessage() . "\n";
    }