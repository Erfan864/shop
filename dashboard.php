<!DOCTYPE html>
<html lang="en">

<head>
    <?php require('./partial/head.php'); ?>
    <title>dashboard</title>
</head>

<body>
    <?php require('./partial/switch_mode.php'); ?>
    <div class="wrapper">
        <h1 id="result">Welcome</h1>
        <p id="message"><?= $_COOKIE['username']; ?>!</p>
        <form action="validation.php" method="POST" id="form">
            <input type="hidden" name="form_type" value="dashboard">
            <div class="box">
                <button class="btn" type="submit">Log Out</button>
            </div>
        </form>
    </div>

</body>

</html>