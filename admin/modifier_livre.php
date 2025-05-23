<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();

if (!$livre) {
    echo "Livre introuvable.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $id_categorie = $_POST['id_categorie'];

    // Si une nouvelle image est envoyée
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp, '../assets/images/' . $image);
    } else {
        $image = $livre['image']; // garder l’ancienne image
    }

    $stmt = $pdo->prepare("UPDATE livres SET titre=?, description=?, image=?, id_categorie=? WHERE id=?");
    $stmt->execute([$titre, $description, $image, $id_categorie, $id]);

    header("Location: dashboard.php");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier le livre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h3 class="mb-4">Modifier le livre</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($livre['titre']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($livre['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select name="id_categorie" class="form-control">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $livre['id_categorie'] ? 'selected' : '' ?>>
                            <?= $cat['nom'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Changer l’image</label>
                <input type="file" name="image" class="form-control">
                <small class="text-muted">Laisser vide pour conserver l’image actuelle</small>
                <div class="mt-2">
                    <img src="../assets/images/<?= $livre['image'] ?>" width="100">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

</body>
</html>

