<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}

$categories = [];
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque', 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $request = $pdo->prepare("SELECT id_categorie, nom_categorie FROM categorie ORDER BY nom_categorie ASC");
    $request->execute();
    $categories = $request->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $error) {
    die($error->getMessage());
}

$message = '';

if (isset($_POST['submit'])) {
    if (!empty($_POST['titre']) && !empty($_POST['joueurs_min']) && !empty($_POST['joueurs_max']) && !empty($_POST['duree']) && !empty($_POST['id_categorie'])) {

        if (filter_var($_POST['joueurs_min'], FILTER_VALIDATE_INT) !== false && filter_var($_POST['joueurs_max'], FILTER_VALIDATE_INT) !== false && filter_var($_POST['duree'], FILTER_VALIDATE_INT) !== false) {

            if ($_POST['joueurs_min'] > 0 && $_POST['joueurs_max'] > 0 && $_POST['duree'] > 0) {

                if ($_POST['joueurs_min'] <= $_POST['joueurs_max']) {

                    $titre = trim($_POST['titre']);
                    $joueurs_min = (int)$_POST['joueurs_min'];
                    $joueurs_max = (int)$_POST['joueurs_max'];
                    $duree = (int)$_POST['duree'];
                    $id_categorie = (int)$_POST['id_categorie'];
                    $id_membre = $_SESSION['id'];

                    try {
                        $request = $pdo->prepare('SELECT titre FROM jeu j WHERE j.titre = ?');
                        $request->bindParam(1, $titre, PDO::PARAM_STR);
                        $request->execute();
                        $data = $request->fetch(PDO::FETCH_ASSOC);

                        if (empty($data)) {

                            $request = $pdo->prepare("INSERT INTO jeu (titre, joueurs_min, joueurs_max, duree, id_categorie, id_membre) VALUES (?, ?, ?, ?, ?, ?)");
                            $request->bindParam(1, $titre, PDO::PARAM_STR);
                            $request->bindParam(2, $joueurs_min, PDO::PARAM_INT);
                            $request->bindParam(3, $joueurs_max, PDO::PARAM_INT);
                            $request->bindParam(4, $duree, PDO::PARAM_INT);
                            $request->bindParam(5, $id_categorie, PDO::PARAM_INT);
                            $request->bindParam(6, $id_membre, PDO::PARAM_INT);
                            $request->execute();

                            $message = "Le jeu a été ajouté avec succès.";

                        } else {
                            $message = "Ce jeu existe déjà.";
                        }

                    } catch (PDOException $error) {
                        die($error->getMessage());
                    }

                } else {
                    $message = "Le nombre minimum de joueurs ne peut pas être supérieur au nombre maximum de joueurs.";
                }

            } else {
                $message = "Veuillez entrer des valeurs supérieures à 0.";
            }

        } else {
            $message = "Veuillez entrer des valeurs entières pour le nombre de joueurs et la durée.";
        }

    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de Jeu</title>
</head>
<body>
    <h1>Ajout de Jeu</h1>

    <?php if (!empty($message)) : ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <label for="titre">Titre jeu <input type="text" name="titre" id="titre"></label>
        <label for="joueurs_min">Nombre minimum de joueurs <input type="number" name="joueurs_min" id="joueurs_min"></label>
        <label for="joueurs_max">Nombre maximum de joueurs <input type="number" name="joueurs_max" id="joueurs_max"></label>
        <label for="duree">Durée (en minutes) <input type="number" name="duree" id="duree"></label>
        <label for="id_categorie">Catégorie
            <select name="id_categorie" id="id_categorie">
                <option value="">Sélectionnez une catégorie</option>
                <?php foreach ($categories as $categorie) : ?>
                    <option value="<?= (int) $categorie['id_categorie'] ?>"><?= htmlspecialchars($categorie['nom_categorie']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <input type="submit" name="submit" value="Ajouter le jeu">
    </form>
</body>
</html>
