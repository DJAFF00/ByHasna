<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

$livres = $pdo->query("SELECT livres.*, categories.nom AS categorie FROM livres 
                       JOIN categories ON livres.id_categorie = categories.id 
                       ORDER BY livres.id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Admin - Livres</a>
        <div class="ms-auto">
            <a href="ajouter_livre.php" class="btn btn-success">+ Ajouter un livre</a>
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="mb-4">Liste des livres</h3>
    <table class="table table-hover table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?= $livre['id'] ?></td>
                    <td><img src="../assets/images/<?= $livre['image'] ?>" width="60" height="60" style="object-fit:cover;"></td>
                    <td><?= htmlspecialchars($livre['titre']) ?></td>
                    <td><?= htmlspecialchars(substr($livre['description'], 0, 80)) ?>...</td>
                    <td><?= $livre['categorie'] ?></td>
                    <td>
                        <a href="modifier_livre.php?id=<?= $livre['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="supprimer_livre.php?id=<?= $livre['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce livre ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
