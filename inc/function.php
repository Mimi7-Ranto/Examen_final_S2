<?php
require('connection.php');

// Inscription d'un membre
function registerUser($nom, $date_naissance, $genre, $email, $mdp, $image_profil) {
    
    $sql = sprintf(
        "INSERT INTO emprunt_membre (nom, date_naissance, gender, email, mots_de_passe, img_prpfile)
         VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
        $nom, $date_naissance, $genre, $email, $mdp, $image_profil
    );
    return mysqli_query(dbconnect(), $sql);
}

// Connexion du membre
function loginUser($email, $mdp) {
    
    $sql = sprintf("SELECT * FROM emprunt_membre WHERE email = '%s'", $email);
    $result = mysqli_query(dbconnect(), $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        if ($mdp === $row['mots_de_passe']) {
            return $row;
        }
    }
    return false;
}

// Récupérer les catégories
function getCategories() {
    
    $sql = "SELECT * FROM emprunt_categorie_objet";
    $result = mysqli_query(dbconnect(), $sql);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

// Récupérer les objets (filtrage par catégorie facultatif)
function getObjets($categorie_id = null) {
    

    if ($categorie_id) {
        $sql = sprintf(
            "SELECT o.*, c.nom_categorie, m.nom AS proprietaire, e.date_retour
             FROM emprunt_objet o
             JOIN emprunt_categorie_objet c ON o.id_categorie = c.id_categorie
             JOIN emprunt_membre m ON o.id_membre = m.id_membre
             LEFT JOIN emprunt_emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL
             WHERE o.id_categorie = %d",
            $categorie_id
        );
    } else {
        $sql = "SELECT o.*, c.nom_categorie, m.nom AS proprietaire, e.date_retour
                FROM emprunt_objet o
                JOIN emprunt_categorie_objet c ON o.id_categorie = c.id_categorie
                JOIN emprunt_membre m ON o.id_membre = m.id_membre
                LEFT JOIN emprunt_emprunt e ON o.id_objet = e.id_objet AND e.date_retour IS NULL";
    }

    $result = mysqli_query(dbconnect(), $sql);
    $objets = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $objets[] = $row;
    }
    return $objets;
}

// Vérifie si un objet est emprunté
function isEmprunte($id_objet) {
    
    $sql = sprintf("SELECT * FROM emprunt_emprunt WHERE id_objet = %d AND date_retour IS NULL", $id_objet);
    $result = mysqli_query(dbconnect(), $sql);
    return mysqli_fetch_assoc($result);
}

// Emprunter un objet
function emprunterObjet($id_objet, $id_membre) {
    
    $sql = sprintf("INSERT INTO emprunt_emprunt (id_objet, id_membre, date_emprunt) VALUES (%d, %d, NOW())",
        $id_objet, $id_membre
    );
    return mysqli_query(dbconnect(), $sql);
}

// Retourner un objet
function retournerObjet($id_objet) {
    
    $sql = sprintf("UPDATE emprunt_emprunt SET date_retour = NOW() WHERE id_objet = %d AND date_retour IS NULL", $id_objet);
    return mysqli_query(dbconnect(), $sql);
}

// Récupérer les images d’un objet
function getImagesObjet($id_objet) {
    
    $sql = sprintf("SELECT nom_image FROM emprunt_image WHERE id_objet = %d", $id_objet);
    $result = mysqli_query(dbconnect(), $sql);
    $images = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row['nom_image'];
    }
    return $images;
}
?>
