<?php
session_start();

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $mdp = $_POST["mdp"];

    // Connexion à la BDD
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot"); // change le mot de passe si nécessaire
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    // Vérifie les identifiants dans la table Administration
    $stmt = $pdo->prepare("SELECT * FROM Administration WHERE login = ? AND mdp = ?");
    $stmt->execute([$login, $mdp]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        // Connexion réussie : on crée la variable de session
        $_SESSION["admin_login"] = $login;
        // Redirige vers admin.php
        header("Location: admin.php");
        exit();
    } else {
        // Identifiants invalides
        $erreur = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='style.css' media='screen'>
    <title>Connexion Administrateur</title>
</head>
<body>
    <h1>Connexion Administrateur</h1>
    <?php if (isset($erreur)) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Login :</label>
        <input type="text" name="login" required><br>
        <label>Mot de passe :</label>
        <input type="password" name="mdp" required><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
