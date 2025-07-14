<?php
require_once('../inc/fonctions.php');

$nom = $_GET['nom'];
$id = get_id_by_name($nom);
$objetData = get_objet_by_id_with_view($id);

if (isset($objetData['error'])) {
    die($objetData['error']);
}

// Vérifier si l'objet existe
if (empty($objetData)) {
    die("Aucun objet trouvé avec cet ID");
}

// Extraire les données de base (premier élément du tableau)
$objet = $objetData[0];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de <?= htmlspecialchars($objet['nom_objet']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <!-- Image principale -->
                <img src="../uploads/<?= htmlspecialchars($objet['nom_image']) ?>"
                    class="main-image w-100 mb-3 rounded"
                    id="mainImage"
                    alt="<?= htmlspecialchars($objet['nom_objet']) ?>">

                <!-- Informations de base -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Informations</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Catégorie:</strong>
                                <span class="badge badge-categorie rounded-pill">
                                    Catégorie #<?= $objet['id_categorie'] ?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <strong>Propriétaire:</strong>
                                Membre #<?= $objet['id_proprietaire'] ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Statut:</strong>
                                <span class="badge bg-<?= $objet['statut_objet'] === 'Retourné' ? 'success' : 'warning' ?>">
                                    <?= $objet['statut_objet'] ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h1><?= htmlspecialchars($objet['nom_objet']) ?></h1>

                <!-- Boutons d'action -->
                <div class="d-flex gap-2 mb-4">
                    <button class="btn btn-primary">Emprunter cet objet</button>
                    <button class="btn btn-outline-secondary">Contacter le propriétaire</button>
                </div>

                <!-- Historique des emprunts -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Historique des emprunts</h3>
                    </div>
                    <div class="card-body">
                        <?php if (count($objetData) > 0): ?>
                            <div class="list-group">
                                <?php foreach ($objetData as $emprunt): ?>
                                    <?php if (!empty($emprunt['id_emprunt'])): ?>
                                        <div class="list-group-item emprunt-card">
                                            <div class="d-flex justify-content-between">
                                                <strong>Emprunteur #<?= $emprunt['id_emprunteur'] ?></strong>
                                                <small class="text-muted">#<?= $emprunt['id_emprunt'] ?></small>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <div>
                                                    <small class="text-success">
                                                        <strong>Emprunté:</strong> <?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?>
                                                    </small>
                                                </div>
                                                <div>
                                                    <small class="<?= $emprunt['statut_objet'] === 'Retourné' ? 'text-success' : 'text-danger' ?>">
                                                        <strong>Retour:</strong> <?= date('d/m/Y', strtotime($emprunt['date_retour'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">Aucun emprunt enregistré pour cet objet</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>