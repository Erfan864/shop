<?php
$host = "localhost";
$dbname = "shop";
$dbUsername = "root";
$dbPassword = "";
//ANCHOR

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "success <br/>";

    function addUser($username, $hashedPassword, $pdo)
    {
        //SEC - to check for the existence of a similar username
        $dbCheck = $pdo->prepare("SELECT * FROM users WHERE username = :username ");
        $dbCheck->execute([':username' => $username]);
        $dbCheckRes = $dbCheck->fetch(PDO::FETCH_ASSOC);
        if (!$dbCheckRes) {
            //SEC - to add users in database
            $dbPush = $pdo->prepare("INSERT INTO users (`ID`, `username`, `pass`, `register_date`, `remember_me` ) VALUES (:ID, :username , :pass , :register_date, :remember_me)");

            $dbPush->execute([
                ':ID' => NULL,
                ':username' => $username,
                ':pass' => $hashedPassword,
                ':register_date' => time(),
                ':remember_me' => false
            ]);
            setcookie("username", $username, time() + (86400 * 1), "/");
            setcookie("isLogin", false, time() + (86400 * 1), "/");
            setcookie("register_date", time(), time() + (86400 * 1), "/");
            setcookie("remember_me", false, time() + (86400 * 1), "/");
            header('Location: login.php');
            //!SEC
        } else {
            setcookie("username", "", time() - 3600, "/");
            setcookie("isLogin", "", time() - 3600, "/");
            setcookie("register_date", "", time() - 3600, "/");
            setcookie("remember_me", "", time() - (86400 * 1), "/");
            header('Location: err.php');
        }
        //!SEC
    }

    function chkUser($username, $password, $rememberMe, $pdo)
    {
        $dbCheck = $pdo->prepare("SELECT * FROM users WHERE username = :username ");
        $dbCheck->execute([':username' => $username]);
        $dbCheckRes = $dbCheck->fetch(PDO::FETCH_ASSOC);
        if ($dbCheckRes != false && password_verify($password, $dbCheckRes['pass'])) {
            setcookie("username", $username, time() + (86400 * 1), "/");
            setcookie("isLogin", true, time() + (86400 * 1), "/");
            setcookie("register_date", time(), time() + (86400 * 1), "/");
            setcookie("remember_me", $rememberMe, time() + (86400 * 1), "/");
            $dbUpdate = $pdo->prepare("UPDATE users SET remember_me = :remember_me WHERE username = :username");
            $dbUpdate->execute([
                ':username' => $username,
                ':remember_me' => $rememberMe
            ]);
            header('Location: dashboard.php');
        } else {
            setcookie("username", "", time() - 3600, "/");
            setcookie("isLogin", "", time() - 3600, "/");
            setcookie("register_date", "", time() - 3600, "/");
            setcookie("remember_me", "", time() + (86400 * 1), "/");
            header('Location: err.php');
        }
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $form_type = $_POST["form_type"] ?? '';
        $username = $_POST['username'];
        $password = $_POST['password-input'];
        if ($form_type === "signup") {
            //SEC - signup codes
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $confirm = $_POST['repeat-password-input'];
            if (validateUsername($username)['valid'] && validatePassword($password)['valid'] && $confirm === $password) {
                addUser($username, $hashedPassword, $pdo);
                // echo 'welcome <strong>' . $username . '</strong> !';
                exit;
            }
            //!SEC
        } elseif ($form_type === "login") {
            $rememberMe = isset($_POST['remember_me']);
            chkUser($username, $password, $rememberMe, $pdo);
        } elseif ($form_type === "dashboard") {
            setcookie("username", "", time() - 3600, "/");
            setcookie("isLogin", "", time() - 3600, "/");
            setcookie("register_date", "", time() - 3600, "/");
            setcookie("remember_me", "", time() + (86400 * 1), "/");
            header('Location: login.php');
            exit;
        }
    }
} catch (PDOException $e) {
    // echo "Error" . $e->getMessage();
}
