<?php
function dbStart()
{
    $host = "localhost";
    $dbname = "shop";
    $dbUsername = "root";
    $dbPassword = "";
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Log error instead of echoing
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}

function dbCheck($username, $pdo)
{
    $dbCheck = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $dbCheck->execute([':username' => $username]);
    return $dbCheck->fetch(PDO::FETCH_ASSOC);
}

function addUser($username, $hashedPassword, $pdo)
{
    $dbCheckRes = dbCheck($username, $pdo);
    if (!$dbCheckRes) {
        $dbPush = $pdo->prepare("INSERT INTO users (`ID`, `username`, `pass`, `created_at`, `remember_me` ) VALUES (:ID, :username , :pass , :created_at, :remember_me)");
        $dbPush->execute([
            ':ID' => NULL,
            ':username' => $username,
            ':pass' => $hashedPassword,
            ':created_at' => time(),
            ':remember_me' => false
        ]);
        setCookies($username);
        header('Location: login.php');
    } else {
        deleteCookies();
        header('Location: err.php');
    }
}

function chkUser($username, $password, $rememberMe, $pdo)
{
    $dbCheckRes = dbCheck($username, $pdo);
    if ($dbCheckRes && password_verify($password, $dbCheckRes['pass'])) {
        setCookies($username, true, $rememberMe);
        updateRememberMe($username, $rememberMe, $pdo);
        header('Location: dashboard.php');
    } else {
        deleteCookies();
        header('Location: err.php');
    }
}

function setCookies($username, $isLogin = false, $rememberMe = false)
{
    $expiry = time() + (86400 * 1); // 1 day
    setcookie("username", $username, $expiry, "/");
    setcookie("isLogin", $isLogin, $expiry, "/");
    setcookie("created_at", time(), $expiry, "/");
    setcookie("remember_me", $rememberMe, $expiry, "/");
}

function deleteCookies()
{
    $past = time() - 3600;
    setcookie("username", "", $past, "/");
    setcookie("isLogin", "", $past, "/");
    setcookie("created_at", "", $past, "/");
    setcookie("remember_me", "", $past, "/");
}

function updateUserLoginTime($username, $pdo)
{
    $dbUpdate = $pdo->prepare("UPDATE users SET created_at = :created_at WHERE username = :username");
    $dbUpdate->execute([
        ':username' => $username,
        ':created_at' => time()
    ]);
}

function updateRememberMe($username, $rememberMe, $pdo)
{
    $dbUpdate = $pdo->prepare("UPDATE users SET remember_me = :remember_me WHERE username = :username");
    $dbUpdate->execute([
        ':username' => $username,
        ':remember_me' => $rememberMe
    ]);
}

function validateUsername($username)
{
    $pattern = "/^[a-zA-Z_][a-zA-Z0-9_]{5,29}$/";

    if (preg_match($pattern, $username)) {
        $Res = [
            "valid" => true,
            "message" => "Username is valid."
        ];
        return $Res;
    } else {
        $Res = [
            "valid" => false,
            "message" => "Username must be at least 6 characters long, only contain letters, numbers, and underscores, not start with a number, and not include spaces or special characters like @, !, #, $."
        ];
        return $Res;
    }
}

function validatePassword($password)
{
    $pattern = "/^(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,30}$/";

    if (preg_match($pattern, $password)) {
        $Res =  [
            "valid" => true,
            "message" => "Password is valid."
        ];
        return $Res;
    } else {
        $Res = [
            "valid" => false,
            "message" => "Password must be at least 8 characters long, contain at least one number and one special character, and must not contain spaces."
        ];
        return $Res;
    }
}

function checkSessionValidity($username, $pdo)
{
    if (!isset($_COOKIE['created_at'])) {
        return false;
    }

    $timeDiff = time() - $_COOKIE['created_at'];
    if ($timeDiff > 86400 * 30) { // 30 days
        deleteCookies();
        return false;
    }

    updateUserLoginTime($username, $pdo);
    return true;
}
