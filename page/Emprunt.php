<?php
require_once '../inc/fonctions.php';

session_start();
// echo '<pre style="color:red">Session ID: ' . (isset($_SESSION['id']) ? $_SESSION['id'] : 'non défini') . '</pre>';


// if (!isset($_SESSION['id'])) {
//     header('Location: login.php');
//     exit();


// Vérifier que les données du formulaire sont présentes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['objet'], $_POST['duree'])) {
    $nom_objet = $_POST['objet'];
    $duree = (int)$_POST['duree'];
    $id_emprunteur = $_SESSION['id'];

    $resultat = creer_emprunt($nom_objet, $id_emprunteur, $duree);

    if ($resultat === true) {
        $message = "Emprunt enregistré avec succès.";
    } else {
        $message = "Erreur lors de l'enregistrement de l'emprunt : $resultat";
    }
} else {
    $message = "Données du formulaire manquantes.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emprunt</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <main class="container mt-5">
        <h1>Emprunt d'objet</h1>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
        <a href="liste.php" class="btn btn-primary">Retour à la liste</a>
    </main>
</body>
</html>
