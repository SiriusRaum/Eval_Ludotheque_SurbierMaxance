<?php 
session_start();
$message = '';

if(isset($_POST['submit'])){

    if(!empty($_POST['email']) && !empty($_POST['password'])){
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $email = trim($_POST['email']);
            try{
                $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                $request = $pdo->prepare('SELECT m.id_membre, m.prenom, m.nom, m.email, m.mot_de_passe FROM membre m WHERE m.email = ?');

                $request->bindParam(1,$email,PDO::PARAM_STR);

                $request->execute();

                $data = $request->fetch(PDO::FETCH_ASSOC);

                if(!empty($data)){
                    if(password_verify($_POST['password'],$data["mot_de_passe"])){
                        $_SESSION['id'] = $data['id_membre'];
                        $_SESSION['nom'] = $data['nom'];
                        $_SESSION['prenom'] = $data['prenom'];
                        $_SESSION['email'] = $data['email'];

                        $message = "{$_SESSION['prenom']} {$_SESSION['nom']} est bien connecté !";
                        header('Location:index.php' );
                    exit();

                    }else{
                        $message = "Email et/ou Mot de Passe incorrect.";
                    }

                }else{
                    $message = "Email et/ou Mot de Passe incorrect.";
                }

            }catch(EXCEPTION $error){
                die($error->getMessage());
            }
        }else{

            $message = 'Vous devez entrer un email valide';
        }
    }else{
        $message = 'Veuillez remplir tous les champs';
    }
}

?>
<?php
include('includes/header.php'); ?>
 <main>
        <h1>Page d'Accueil</h1>
        
        <?php echo($message) ?>


        <?php 
            if(!isset($_SESSION['id'])){
        ?>
                <section>
                    <h2>Connexion</h2>
                    <form action="" method="post">
                        <label for="email">Email <input type="text" name="email" id="email"></label>
                        <label for="password">Mot de Passe <input type="text" name="password" id="password"></label>
                        <input type="submit" name="submit">
                    </form>
                    </section>
                    <?php } ?>
                
</main>
<?php
include('includes/footer.php'); ?>
