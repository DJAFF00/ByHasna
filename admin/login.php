<?php
session_start();
require_once '../includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['mot_de_passe'])) {
        $_SESSION['admin'] = $admin['username'];
        header('Location: dashboard.php');
        exit();
    } else {
        $message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f8;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card shadow p-4">
        <h3 class="mb-4 text-center text-primary">Espace Admin</h3>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control" placeholder="Entrez votre nom" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</div>

</body>
</html>
