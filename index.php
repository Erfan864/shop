<!DOCTYPE html>
<html lang="en">

<head>
    <?php require('./partial/head.php'); ?>
    <title>SIGNUP FORM</title>
</head>

<body>
    <?php 
    require('./partial/switch_mode.php');
    require_once('./partial/main.php');
    
    $pdo = dbStart();
    if (!$pdo) {
        header('Location: err.php');
        exit;
    }

    if (isset($_COOKIE['username'])) {
        if (checkSessionValidity($_COOKIE['username'], $pdo)) {
            header('Location: login.php');
            exit;
        }
    } else {
        deleteCookies();
    }
    ?>
    <div class="wrapper">
        <h1>Signup</h1>
        <p id="error-message"></p>
        <form action="validation.php" method="POST" id="form">
            <input type="hidden" name="form_type" value="signup">
            <div>
                <label for="username">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z" />
                    </svg>
                </label>
                <input type="text" name="username" id="username" placeholder="Username" required>
            </div>
            <div>
                <label for="password-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
                    </svg>
                </label>
                <input type="password" name="password-input" id="password-input" placeholder="Password" required>
            </div>
            <div>
                <label for="repeat-password-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
                    </svg>
                </label>
                <input type="password" name="repeat-password-input" id="repeat-password-input" placeholder="Repeat Password" required>
            </div>
            <div id="signupMessage"></div>
            <button type="submit">Signup</button>
        </form>
        <p>Already have an Account? <a href="login.php">login</a> </p>
    </div>

</body>

</html>