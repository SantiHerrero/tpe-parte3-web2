<?php

require_once __DIR__ . '/../../config.php';
try {
    // si no esta la base la crea
    $pdo = new PDO('mysql:host='.DB_HOST, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS '.DB_NAME.' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $pdo->exec('USE ' . DB_NAME);
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($sql);

    // si no existe admin lo crea
    $stmt = $pdo->prepare('SELECT COUNT(*) as c FROM usuarios WHERE nombre_usuario = ?');
    $stmt->execute(['webadmin']);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r || $r['c'] == 0) {
        $hash = password_hash('admin', PASSWORD_DEFAULT);
        $pdo->prepare('INSERT INTO usuarios (nombre_usuario,nombre,apellido,tipo,contrasenia) VALUES (?,?,?,?,?)')
            ->execute(['webadmin','Admin','Web',1,$hash]);
    }
} catch (PDOException $e) {
    echo 'DB init error: '.$e->getMessage();
    exit;
}
