<?php
session_start();
require_once("functions.php");

$categories = getCategories();
$categorie_id = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
$objets = getObjets($categorie_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Objets</title>
 <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #f7f7f7;
        }
        .card {
            margin-bottom: 20px;
            border-radius: 15px;
        }
        .status-dispo {
            color: green;
            font-weight: bold;
        }
        .status-indispo {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4 text-center">📦 Liste des objets à emprunter</h1>

    <!-- Filtre par catégorie -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-6">
            <select name="categorie" class="form-select">
                <option value="">-- Toutes les catégories --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= ($categorie_id == $cat['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
        <div class="col-md-2">
            <a href="liste_objets.php" class="btn btn-secondary">Réinitialiser</a>
        </div>
    </form>

    <!-- Liste des objets -->
    <div class="row">
        <?php if (empty($objets)): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">Aucun objet trouvé.</div>
            </div>
        <?php else: ?>
            <?php foreach ($objets as $obj): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($obj['nom_objet']) ?></h5>
                            <p class="card-text">
                                <strong>Catégorie :</strong> <?= htmlspecialchars($obj['nom_categorie']) ?><br>
                                <strong>Propriétaire :</strong> <?= htmlspecialchars($obj['proprietaire']) ?><br>
                                <strong>Statut :</strong>
                                <?php if ($obj['date_retour'] === null && isEmprunte($obj['id_objet'])): ?>
                                    <span class="status-indispo">Emprunté</span>
                                <?php else: ?>
                                    <span class="status-dispo">Disponible</span>
                                <?php endif; ?>
                            </p>
                            <?php
                            $images = getImagesObjet($obj['id_objet']);
                            if (!empty($images)) {
                                echo '<img src="uploads/' . htmlspecialchars($images[0]) . '" class="img-fluid rounded">';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
