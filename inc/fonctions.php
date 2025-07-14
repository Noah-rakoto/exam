<?php
require_once '../inc/connexion.php';

function creerMembre($nom, $date_naissance, $genre, $email, $ville, $mdp)
{
    $conn = dbconnect();
    $sql = "INSERT INTO emp_membre (nom, date_naissance, genre, email, ville, mdp, image_profil)
            VALUES ('$nom', '$date_naissance', '$genre', '$email', '$ville', '$mdp', 'assets/image/default.png')";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    return $result;
}

function creer_emprunt($nom_objet, $id_emprunteur, $duree)
{
    $conn = dbconnect();
    // Récupérer l'id de l'objet
    $sql_objet = "SELECT id_objet FROM emp_objet WHERE nom_objet = '" . mysqli_real_escape_string($conn, $nom_objet) . "'";
    $res_objet = mysqli_query($conn, $sql_objet);
    if (!$res_objet || mysqli_num_rows($res_objet) === 0) {
        mysqli_close($conn);
        return "Objet introuvable.";
    }
    $row = mysqli_fetch_assoc($res_objet);
    $id_objet = $row['id_objet'];

    // Vérifier la disponibilité : on regarde la date de retour la plus récente
    $sql_last_emprunt = "SELECT date_retour FROM emp_emprunt WHERE id_objet = $id_objet ORDER BY date_retour DESC LIMIT 1";
    $res_last = mysqli_query($conn, $sql_last_emprunt);
    if ($res_last && ($row_last = mysqli_fetch_assoc($res_last))) {
        if ($row_last['date_retour'] !== null && $row_last['date_retour'] >= date('Y-m-d')) {
            mysqli_close($conn);
            return "Objet déjà emprunté.";
        }
    }

    // Calculer la date de retour
    $date_emprunt = date('Y-m-d');
    $date_retour = date('Y-m-d', strtotime("+$duree days"));

    // Insérer l'emprunt
    $sql_emprunt = "INSERT INTO emp_emprunt (id_objet, id_emprunteur, date_emprunt, date_retour) VALUES ($id_objet, $id_emprunteur, '$date_emprunt', '$date_retour')";
    if (!mysqli_query($conn, $sql_emprunt)) {
        $err = mysqli_error($conn);
        mysqli_close($conn);
        return $err;
    }

    // Mettre à jour le statut de l'objet
    $sql_update = "UPDATE emp_objet SET statut = 'Emprunté', date_retour = '$date_retour' WHERE id_objet = $id_objet";
    mysqli_query($conn, $sql_update);

    mysqli_close($conn);
    return true;
}
function verifycompte($email, $mdp)
{
    $conn = dbconnect();
    $req = "SELECT * FROM emp_membre WHERE email = '$email' AND mdp = '$mdp'";
    $result = mysqli_query($conn, $req);
    return mysqli_num_rows($result) > 0;
}
function getidbyemail($email)
{
    $conn = dbconnect();
    $req = "SELECT id_membre FROM emp_membre WHERE email = '$email'";
    $result = mysqli_query($conn, $req);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['id_membre'];
    }
    return null;
}
function listeObjets()
{
    $conn = dbconnect();
    $req = "SELECT * FROM emp_view_objet_and_membre_and_emprunt";
    $result = mysqli_query($conn, $req);
    $objets = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $objets[] = $row;
    }
    return $objets;
}
/**
 * Récupère toutes les catégories disponibles
 */
function get_categories()
{
    $conn = dbconnect();
    $req = "SELECT * FROM emp_categorie_objet ORDER BY nom_categorie";
    $result = mysqli_query($conn, $req);

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

/**
 * Liste tous les objets avec leur statut
 */
function lister_tous_objets()
{
    $conn = dbconnect();
    $req = "SELECT * FROM emp_view_objets_complet";
    $result = mysqli_query($conn, $req);

    $objets = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $objets[] = $row;
    }
    return $objets;
}

/**
 * Filtre les objets par catégorie
 */
function filtrer_objets_par_categorie($categorie)
{
    $conn = dbconnect();
    $req = "SELECT * FROM emp_view_objets_complet 
            WHERE nom_categorie = '" . mysqli_real_escape_string($conn, $categorie) . "'";
    $result = mysqli_query($conn, $req);

    $objets = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $objets[] = $row;
    }
    return $objets;
}
function get_by_name($name)
{
    $conn = dbconnect();
    $req = "SELECT id_membre FROM emp_membre WHERE nom = '" . mysqli_real_escape_string($conn, $name) . "'";
    $result = mysqli_query($conn, $req);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['id_membre'];
    }
    return null;
}
function get_objet_by_id_with_view($id_objet)
{
    $conn = dbconnect();
    if (!$conn) {
        return ['error' => 'Database connection failed'];
    }

    // Secure the input by forcing it to be an integer
    $id_objet = intval($id_objet);
    $req = "SELECT * FROM vue_objet_complet_inner WHERE id_objet = $id_objet";

    $result = mysqli_query($conn, $req);

    // Check if query failed
    if ($result === false) {
        return ['error' => 'Database query failed: ' . mysqli_error($conn)];
    }

    // Check if no results found
    if (mysqli_num_rows($result) === 0) {
        return ['error' => 'No object found with ID: ' . $id_objet];
    }

    $objets = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $objets[] = $row;
    }

    mysqli_free_result($result);
    return $objets;
}

function get_id_by_name($name)
{
    $conn = dbconnect();
    $req = "SELECT id_objet FROM emp_objet WHERE nom_objet = '" . mysqli_real_escape_string($conn, $name) . "'";
    $result = mysqli_query($conn, $req);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['id_objet'];
    }
    return null;
}
function info_membre($id)
{
    $conn = dbconnect();
    $id = intval($id);

    // 1. Récupérer les infos de base du membre
    $req_membre = "SELECT * FROM emp_membre WHERE id_membre = $id";
    $result_membre = mysqli_query($conn, $req_membre);

    if (!$result_membre || mysqli_num_rows($result_membre) === 0) {
        return ['error' => 'Membre non trouvé'];
    }

    $membre = mysqli_fetch_assoc($result_membre);

    // 2. Récupérer les objets du membre regroupés par catégorie
    $req_objets = "SELECT 
                    o.id_objet, 
                    o.nom_objet, 
                    c.id_categorie, 
                    c.nom_categorie,
                    (SELECT COUNT(*) FROM emp_emprunt e WHERE e.id_objet = o.id_objet) AS nb_emprunts,
                    (SELECT MAX(date_emprunt) FROM emp_emprunt e WHERE e.id_objet = o.id_objet) AS dernier_emprunt
                  FROM emp_objet o
                  JOIN emp_categorie_objet c ON o.id_categorie = c.id_categorie
                  WHERE o.id_membre = $id
                  ORDER BY c.nom_categorie, o.nom_objet";

    $result_objets = mysqli_query($conn, $req_objets);
    $objets_par_categorie = [];

    while ($row = mysqli_fetch_assoc($result_objets)) {
        $categorie_id = $row['id_categorie'];
        if (!isset($objets_par_categorie[$categorie_id])) {
            $objets_par_categorie[$categorie_id] = [
                'nom_categorie' => $row['nom_categorie'],
                'objets' => []
            ];
        }
        $objets_par_categorie[$categorie_id]['objets'][] = $row;
    }

    // 3. Récupérer les emprunts en cours du membre
    $req_emprunts = "SELECT 
                       o.id_objet, 
                       o.nom_objet,
                       e.date_emprunt,
                       e.date_retour
                     FROM emp_emprunt e
                     JOIN emp_objet o ON e.id_objet = o.id_objet
                     WHERE e.id_membre = $id AND e.date_retour > CURDATE()";

    $result_emprunts = mysqli_query($conn, $req_emprunts);
    $emprunts_en_cours = mysqli_fetch_all($result_emprunts, MYSQLI_ASSOC);

    return [
        'membre' => $membre,
        'objets_par_categorie' => $objets_par_categorie,
        'emprunts_en_cours' => $emprunts_en_cours,
        'nb_objets' => mysqli_num_rows($result_objets),
        'nb_emprunts' => count($emprunts_en_cours)
    ];
}
