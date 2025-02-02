<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie do budżetu</title>
    <link rel="stylesheet" href="css/loginStyles.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="assets/logo.png" alt="Logo">
        </div>
        <div class="login-box">
            <h1>Logowanie</h1>
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="error">
                    Błędna nazwa użytkownika lub hasło.
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="textbox">
                    <input type="text" name="username" placeholder="Nazwa użytkownika" required>
                </div>
                <div class="textbox">
                    <input type="password" name="password" placeholder="Hasło" required>
                </div>
                <input type="submit" class="btn" value="Zaloguj">
            </form>
        </div>
    </div>
</body>
</html>