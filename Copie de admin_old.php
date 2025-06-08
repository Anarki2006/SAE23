<?php
session_start();

// Vérifie si l'admin est connecté.
if (!isset($_SESSION["admin_login"])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot"); // change le mot de passe si besoin
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Requête pour récupérer la liste des bâtiments
$sql = "SELECT * FROM Batiment";
$stmt = $pdo->query($sql);
$batiments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Bâtiments</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
    </style>
</head>
<body>
    <h1>Page d'administration - Liste des Bâtiments</h1>

    <p>Connecté en tant que : <?php echo htmlspecialchars($_SESSION["admin_login"]); ?></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Login</th>
                <th>Mot de passe</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($batiments as $bat) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($bat["id_batiment"]); ?></td>
                    <td><?php echo htmlspecialchars($bat["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($bat["login"]); ?></td>
                    <td><?php echo htmlspecialchars($bat["mdp"]); ?></td>
                    <td>
                        <a href="modifier_batiment.php?id=<?php echo $bat["id_batiment"]; ?>">Modifier</a> |
                        <a href="supprimer_batiment.php?id=<?php echo $bat["id_batiment"]; ?>" onclick="return confirm('Supprimer ce bâtiment ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="ajouter_batiment.php">Ajouter un nouveau bâtiment</a></p>
    <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>
