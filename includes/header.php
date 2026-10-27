
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
        <?php 
            if(!isset($_SESSION['id'])){
        ?>
<body>
    <header>
<nav>
    <ul>
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="../inscription.php">Inscription</a></li>
        <li><a href="../connexion.php" >Connexion</a></li>
    </ul>
</nav>
<?php } 
    else { ?>
        <nav>
        <ul>
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="../ajout-jeu.php">Ajout Jeu</a></li>
        <li><a href="../deconnexion.php">Déconnexion</a></li>
        
    </ul>
</nav>

<?php  } ?>
    </header>