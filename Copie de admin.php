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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration complète</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
        h2 { margin-top: 30px; }
    </style>
</head>
<body>
    <h1>Page d'administration complète</h1>
    <p>Connecté en tant que : <?php echo htmlspecialchars($_SESSION["admin_login"]); ?></p>

    <!-- 🔹 Liste des Bâtiments -->
    <h2>Liste des Bâtiments</h2>
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
            <?php
            $stmt = $pdo->query("SELECT * FROM Batiment");
            $batiments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($batiments as $bat) :
            ?>
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

    <!-- 🔹 Liste des Capteurs -->
    <h2>Liste des Capteurs</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Type</th>
                <th>Unité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM Capteur");
            $capteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($capteurs as $cap) :
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($cap["id_capteur"]); ?></td>
                    <td><?php echo htmlspecialchars($cap["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($cap["type"]); ?></td>
                    <td><?php echo htmlspecialchars($cap["unite"]); ?></td>
                    <td>
                        <a href="modifier_capteur.php?id=<?php echo $cap["id_capteur"]; ?>">Modifier</a> |
                        <a href="supprimer_capteur.php?id=<?php echo $cap["id_capteur"]; ?>" onclick="return confirm('Supprimer ce capteur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="ajouter_capteur.php">Ajouter un nouveau capteur</a></p>

    <!-- 🔹 Liste des Salles -->
    <h2>Liste des Salles</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Capacité</th>
                <th>ID Bâtiment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM Salle");
            $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($salles as $salle) :
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($salle["nom"]); ?></td>
                    <td><?php echo htmlspecialchars($salle["type"]); ?></td>
                    <td><?php echo htmlspecialchars($salle["capacite"]); ?></td>
                    <td><?php echo htmlspecialchars($salle["id_batiment"]); ?></td>
                    <td>
                        <a href="modifier_salle.php?nom=<?php echo urlencode($salle["nom"]); ?>">Modifier</a> |
                        <a href="supprimer_salle.php?nom=<?php echo urlencode($salle["nom"]); ?>" onclick="return confirm('Supprimer cette salle ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="ajouter_salle.php">Ajouter une nouvelle salle</a></p>

    <!-- 🔹 20 dernières mesures -->
    <h2>20 dernières Mesures</h2>
    <table>
        <thead>
            <tr>
                <th>ID Mesure</th>
                <th>Valeur</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Nom Capteur</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT m.id_mesure, m.valeur, m.date, m.heure, c.nom AS capteur_nom
                    FROM Mesure m
                    JOIN Capteur c ON m.id_capteur = c.id_capteur
                    ORDER BY m.date DESC, m.heure DESC
                    LIMIT 20";
            $stmt = $pdo->query($sql);
            $mesures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($mesures as $mes) :
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($mes["id_mesure"]); ?></td>
                    <td><?php echo htmlspecialchars($mes["valeur"]); ?></td>
                    <td><?php echo htmlspecialchars($mes["date"]); ?></td>
                    <td><?php echo htmlspecialchars($mes["heure"]); ?></td>
                    <td><?php echo htmlspecialchars($mes["capteur_nom"]); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>


