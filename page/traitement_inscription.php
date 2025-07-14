<?php
require_once '../inc/connexion.php';
require_once '../inc/fonctions.php';
session_start();

// Vérification basique des données
if (empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password'])) {
    die("Tous les champs obligatoires doivent être remplis");
}

$nom = $_POST['nom'];
$date_naissance = $_POST['date'];
$genre = $_POST['genre'];
$email = $_POST['email'];
$ville = $_POST['ville'];
$mdp = $_POST['password'];
$_SESSION['id'] = getidbyemail($email);
if (creerMembre($nom, $date_naissance, $genre, $email, $ville, $mdp)) {
    header("Location: liste.php");
    exit();
} else {
    // Stocker les erreurs en session pour les afficher sur la page d'inscription
    $_SESSION['erreur_inscription'] = "Erreur lors de l'inscription. Veuillez réessayer.";
    header("Location: inscription.php");
    exit();
}
