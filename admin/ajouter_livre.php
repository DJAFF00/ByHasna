<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $id_categorie = $_POST['id_categorie'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, '../assets/images/' . $image);

    $stmt = $pdo->prepare("INSERT INTO livres (titre, description, image, id_categorie) VALUES (?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $image, $id_categorie]);

    header('Location: dashboard.php');
    exit();
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un livre</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Ajouter un nouveau livre</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Titre</label>
            <input type="text" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Catégorie</label>
            <select name="id_categorie" class="form-control" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['nom'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="dashboard.php" class="btn btn-secondary">Retour</a>
    </form>
</body>
</html>
