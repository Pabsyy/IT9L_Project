<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306", "root", "");
    $pdo->exec("CREATE DATABASE IF NOT EXISTS underthehood");
    echo "Database created successfully\n";
} catch (PDOException $e) {
    echo "Error creating database: " . $e->getMessage() . "\n";
} 