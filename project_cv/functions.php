<?php
require_once 'config.php';

function getUserByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getDefaultUser() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users WHERE is_default = 1 LIMIT 1");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUserCV($user_id, $data) {
    global $pdo;
    $sql = "UPDATE users SET tentang=?, pendidikan=?, keahlian=?, proyek=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$data['tentang'], $data['pendidikan'], $data['keahlian'], $data['proyek'], $user_id]);
}

function setDefaultUser($user_id) {
    global $pdo;
    $pdo->query("UPDATE users SET is_default = 0");
    $stmt = $pdo->prepare("UPDATE users SET is_default = 1 WHERE id = ?");
    return $stmt->execute([$user_id]);
}

function getAllUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createUser($username, $password, $nama, $email, $role='user') {
    global $pdo;
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, email, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$username, $hashed, $nama, $email, $role]);
}

function deleteUser($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}
?>