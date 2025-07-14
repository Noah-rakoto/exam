<?php
require_once '../inc/connexion.php';
require_once '../inc/fonctions.php';
session_start();
$id_co = $_SESSION['id'];
$categorie_filtre = $_GET['categorie'] ?? null;
$objets = $categorie_filtre ? filtrer_objets_par_categorie($categorie_filtre) : lister_tous_objets();
$categories = get_categories();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des objets</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>


<body>
    <main class="container py-4">
        <h1 class="mb-4 text-center">Liste des objets</h1>

        <!-- Formulaire de filtrage stylisé -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <form method="get" class="d-flex gap-2 align-items-center">
                    <select name="categorie" class="form-select">
                        <option value="">Toutes catégories</option>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= htmlspecialchars($categorie['nom_categorie']) ?>"
                                <?= ($categorie_filtre === $categorie['nom_categorie']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categorie['nom_categorie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <?php if ($categorie_filtre): ?>
                        <a href="?" class="btn btn-outline-secondary">Tout afficher</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Objet</th>
                        <th>Catégorie</th>
                        <th>Propriétaire</th>
                        <th>Statut</th>
                        <th>Emprunt</th>
                        <th>Retour prévu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($objets as $objet): ?>
                        <tr>
                            <td>
                                <a href="fiche_obj.php?nom=<?= urlencode($objet['nom_objet']) ?>" class="object-link text-decoration-none">
                                    <?= htmlspecialchars($objet['nom_objet']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
                            <td>
                                <a href="fiche_personne.php?nom=<?= urldecode($objet['proprietaire']) ?>" class="objet-link text-decoration-none">
                                    <?= htmlspecialchars($objet['proprietaire']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge <?= $objet['statut'] === 'Disponible' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= htmlspecialchars($objet['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($objet['statut'] === 'Disponible'): ?>
                                    <form method="post" action="Emprunt.php" class="d-flex gap-2 align-items-center" style="display:inline;">
                                        <input type="hidden" name="objet" value="<?= htmlspecialchars($objet['nom_objet']) ?>">
                                        <input type="number" name="duree" min="1" max="30" value="1" class="form-control" style="width:70px;" required> j
                                        <button type="submit" class="btn btn-success btn-sm">Emprunter</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">Indisponible</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $objet['date_retour'] ?? '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>