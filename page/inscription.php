<?php

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/boostrap-icon/bootstrap-icons.css">
</head>

<body>
    <div class="container">
        <div class="registration-card">
            <div class="registration-header">
                <h2><i class="bi bi-person-plus-fill me-2"></i>Créer un compte</h2>
            </div>
            <div class="registration-body">
                <form action="traitement_inscription.php" method="post">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" name="nom" id="nom" required placeholder="Votre nom complet">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" name="email" id="email" required placeholder="exemple@domaine.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" id="password" required placeholder="Créez un mot de passe">
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>

                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <select class="form-select" name="genre" id="genre" required>
                            <option value="" disabled selected>Sélectionnez votre genre</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ville" class="form-label">Ville</label>
                        <input type="text" class="form-control" name="ville" id="ville" required placeholder="Votre ville de résidence">
                    </div>

                    <button type="submit" class="btn btn-primary btn-register w-100">
                        <i class="bi bi-person-plus me-2"></i>S'inscrire
                    </button>

                    <div class="login-link">
                        <p>Déjà un compte? <a href="login.php">Se connecter</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>