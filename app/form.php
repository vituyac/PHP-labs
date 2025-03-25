<?php
session_start();
require 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $gender = $_POST['gender'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $gym = $_POST['gym'];

    $stmt = $pdo->prepare("INSERT INTO users_comments (name, age, gender, phone, email, gym) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $age, $gender, $phone, $email, $gym]);

    $_SESSION['message'] = "Данные успешно сохранены";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    $stmt = $pdo->query("SELECT name, age, gender, phone, email, gym FROM users_comments");
    $trainers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($trainers);
    exit();
}
