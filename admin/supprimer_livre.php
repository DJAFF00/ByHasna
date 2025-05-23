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

// On récupère le livre pour supprimer aussi l’image
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();

if ($livre) {
    // Supprimer l’image du dossier si elle existe
    $chemin_image = '../assets/images/' . $livre['image'];
    if (file_exists($chemin_image)) {
        unlink($chemin_image);
    }

    // Supprimer le livre de la base
    $stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: dashboard.php');
exit();
