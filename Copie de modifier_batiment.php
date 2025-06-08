<?php
session_start();

// Vérifie si l'admin est connecté
if (!isset($_SESSION["admin_login"])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie si un id de bâtiment est fourni en GET
if (!isset($_GET["id"])) {
    die("ID de bâtiment manquant.");
}

$id_batiment = $_GET["id"];

// Récupère les infos du bâtiment actuel
$stmt = $pdo->prepare("SELECT * FROM Batiment WHERE id_batiment = ?");
$stmt->execute([$id_batiment]);
$batiment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$batiment) {
    die("Bâtiment non trouvé.");
}

// Vérifie si le formulaire a été soumis pour modifier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $login = $_POST["login"];
    $mdp = $_POST["mdp"];

    // Update dans la BDD
    $update = $pdo->prepare("UPDATE Batiment SET nom = ?, login = ?, mdp = ? WHERE id_batiment = ?");
    $update->execute([$nom, $login, $mdp, $id_batiment]);

    // Redirige vers admin.php
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Bâtiment</title>
</head>
<body>
    <h1>Modifier le Bâtiment</h1>

    <form method="post">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($batiment["nom"]); ?>" required><br>
        <label>Login :</label>
        <input type="text" name="login" value="<?php echo htmlspecialchars($batiment["login"]); ?>" required><br>
        <label>Mot de passe :</label>
        <input type="text" name="mdp" value="<?php echo htmlspecialchars($batiment["mdp"]); ?>" required><br>
        <button type="submit">Modifier</button>
    </form>

    <p><a href="admin.php">Retour à l'administration</a></p>
</body>
</html>


