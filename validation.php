<?php
require_once('./partial/main.php');

$pdo = dbStart();
if (!$pdo) {
    header('Location: err.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = $_POST["form_type"] ?? '';
    $username = $_POST['username'];
    $password = $_POST['password-input'];

    if ($form_type === "signup") {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $confirm = $_POST['repeat-password-input'];
        
        if (validateUsername($username)['valid'] && 
            validatePassword($password)['valid'] && 
            $confirm === $password) {
            addUser($username, $hashedPassword, $pdo);
            exit;
        }
        header('Location: err.php');
        exit;
    } elseif ($form_type === "login") {
        $rememberMe = isset($_POST['remember_me']);
        chkUser($username, $password, $rememberMe, $pdo);
    } elseif ($form_type === "dashboard") {
        deleteCookies();
        header('Location: login.php');
        exit;
    }
}
