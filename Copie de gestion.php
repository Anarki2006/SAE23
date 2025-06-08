<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $mdp = $_POST["mdp"];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifie dans la table Batiment
        $stmt = $pdo->prepare("SELECT * FROM Batiment WHERE login = ? AND mdp = ?");
        $stmt->execute([$login, $mdp]);
        $batiment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($batiment) {
            $_SESSION["gestion_login"] = $login;
            $_SESSION["id_batiment"] = $batiment["id_batiment"];
            header("Location: gestion_dashboard.php");
            exit();
        } else {
            $erreur = "Identifiants incorrects.";
        }
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Gestionnaire</title>
</head>
<body>
    <h1>Connexion Gestionnaire</h1>
    <?php if (isset($erreur)) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Login :</label><input type="text" name="login" required><br>
        <label>Mot de passe :</label><input type="password" name="mdp" required><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>


