<?php
session_start();
$jeux = [''];
require_once('utils/utils.php');
if (isset($_SESSION['id'])){
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','root',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        try{

    $request = $pdo->prepare("SELECT j.id_jeu, j.titre , j.joueurs_min, j.joueurs_max, j.duree, c.nom_categorie, m.prenom FROM jeu j
        INNER JOIN membre m ON m.id_membre = j.id_membre
        INNER JOIN categorie c ON c.id_categorie = j.id_categorie
        ORDER BY j.titre ASC");

    $request->execute();

    $jeux = $request->fetchAll(PDO::FETCH_ASSOC);



}catch(EXCEPTION $error){

    die($error->getMessage());


}


}else{
    echo "Vous devez être connecté pour accéder au catalogue.";

}

include('includes/header.php');
?>
<?php if(isset($_SESSION['id'])) { ?>

<main>
    <h1>Liste de jeux</h1>
    <?php foreach ($jeux as $jeu) : ?>
        <article>
            <h2>
                <?= $jeu['titre'] ?>
                <?= $jeu['nom_categorie'] ?>

    </h2>
                <p> joueurs : <?= formatJoueurs($jeu['joueurs_min'], $jeu['joueurs_max']) ;?> </p>
                <p> Durée : <?= categorieDuree($jeu['duree']); ?> </p>
                <p> Ajouté par : <?= $jeu['prenom']; ?> </p>
    </article>
    
    <?php endforeach; ?>
    </main>
<?php } include('includes/footer.php'); ?>