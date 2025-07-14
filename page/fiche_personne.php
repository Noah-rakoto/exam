<?php
require_once '../inc/fonctions.php';
$nom = $_GET['nom'] ?? '';
$id =  get_by_name($nom);
$infos = info_membre($id);
if (isset($infos['error'])) {
    die($infos['error']);
}

$membre = $infos['membre'];
$objets_par_categorie = $infos['objets_par_categorie'];
$emprunts_en_cours = $infos['emprunts_en_cours'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($membre['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
        }

        .category-card {
            margin-bottom: 2rem;
            border-left: 4px solid #6a11cb;
        }

        .object-card {
            transition: transform 0.2s;
        }

        .object-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <!-- En-tête du profil -->
        <div class="profile-header text-center">
            <?php if (!empty($membre['image_profil'])): ?>
                <img src="../uploads/profils/<?= htmlspecialchars($membre['image_profil']) ?>"
                    class="profile-pic mb-3"
                    alt="Photo de profil">
            <?php else: ?>
                <div class="profile-pic mb-3 mx-auto bg-light d-flex align-items-center justify-content-center">
                    <i class="bi bi-person-fill" style="font-size: 4rem; color: #6c757d;"></i>
                </div>
            <?php endif; ?>

            <h1><?= htmlspecialchars($membre['nom']) ?></h1>
            <p class="lead">Membre depuis <?= date('Y', strtotime($membre['date_inscription'] ?? 'now')) ?></p>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <h5><?= $infos['nb_objets'] ?></h5>
                        <p class="text-muted">Objets partagés</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <h5><?= $infos['nb_emprunts'] ?></h5>
                        <p class="text-muted">Emprunts en cours</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <h5><?= count($objets_par_categorie) ?></h5>
                        <p class="text-muted">Catégories</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Objets par catégorie -->
        <h2 class="mb-4">Objets partagés</h2>

        <?php if (empty($objets_par_categorie)): ?>
            <div class="alert alert-info">Ce membre n'a partagé aucun objet pour le moment.</div>
        <?php else: ?>
            <?php foreach ($objets_par_categorie as $categorie): ?>
                <div class="card category-card mb-4">
                    <div class="card-header bg-white">
                        <h3 class="h5 mb-0">
                            <?= htmlspecialchars($categorie['nom_categorie']) ?>
                            <span class="badge bg-primary ms-2"><?= count($categorie['objets']) ?></span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($categorie['objets'] as $objet): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card object-card h-100">
                                        <div class="card-body">
                                            <h4 class="h6 card-title">
                                                <a href="fiche_obj.php?id=<?= $objet['id_objet'] ?>">
                                                    <?= htmlspecialchars($objet['nom_objet']) ?>
                                                </a>
                                            </h4>
                                            <p class="card-text small text-muted">
                                                Emprunté <?= $objet['nb_emprunts'] ?> fois
                                            </p>
                                            <?php if ($objet['dernier_emprunt']): ?>
                                                <p class="card-text small">
                                                    Dernier emprunt: <?= date('d/m/Y', strtotime($objet['dernier_emprunt'])) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Emprunts en cours -->
        <h2 class="mb-4">Emprunts en cours</h2>

        <?php if (empty($emprunts_en_cours)): ?>
            <div class="alert alert-info">Aucun emprunt en cours.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Objet</th>
                            <th>Date d'emprunt</th>
                            <th>Date de retour prévue</th>
                            <th>Jours restants</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emprunts_en_cours as $emprunt): ?>
                            <?php
                            $jours_restants = floor((strtotime($emprunt['date_retour']) - time()) / (60 * 60 * 24));
                            $class_jours = $jours_restants <= 3 ? 'text-danger' : 'text-success';
                            ?>
                            <tr>
                                <td>
                                    <a href="fiche_obj.php?id=<?= $emprunt['id_objet'] ?>">
                                        <?= htmlspecialchars($emprunt['nom_objet']) ?>
                                    </a>
                                </td>
                                <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($emprunt['date_retour'])) ?></td>
                                <td class="<?= $class_jours ?>">
                                    <?= $jours_restants > 0 ? $jours_restants : 'Dépassé' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>