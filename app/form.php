<?php
    session_start();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name']);
        $age = trim($_POST['age']);
        $gender = $_POST['gender'];
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);

        $csvFile = 'data.csv';

        $dataRow = [$name, $age, $gender, $phone, $email];

        if (($file = fopen($csvFile, 'a')) !== false) {
            fputcsv($file, $dataRow);
            fclose($file);
            $_SESSION['message'] = "Данные успешно сохранены";
        } else {
            $_SESSION['message'] = "Ошибка при сохранении данных";
        }

        header("Location: index.php");
        exit();
    }    
?>