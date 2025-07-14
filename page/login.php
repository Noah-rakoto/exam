<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>Document</title>
</head>

<body>
    <main>
        <form action="traitement_login.php" method="post">
            <label for="">
                <p>email</p>
            </label>
            <input type="email" name="email" id="" required placeholder="Enter your email">
            <label for="">
                <p>Mot de passe</p>
            </label>
            <input type="password" name="password" id="" required placeholder="Enter your password">
            <button type="submit">Login</button>
            <p>Creer un compte? <a href="inscription.php">S'inscrire</a></p>
        </form>
    </main>

    <footer>

    </footer>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>