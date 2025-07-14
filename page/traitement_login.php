<?php
require_once '../inc/connexion.php';
require_once '../inc/fonctions.php';
session_start();

$email = $_POST['email'];
$mdp = $_POST['password'];
if (verifycompte($email, $mdp)) {
    $_SESSION['email'] = $email;
    $_SESSION['id'] = getidbyemail($email);
    header("location: liste.php");
    exit();
} else {
    header("location: login.php?error=1");
    exit();
}
