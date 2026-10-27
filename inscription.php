<?php 
session_start();
$message = '';

if(isset($_POST['submit'])){

    if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['dumypassword']) ){
        
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){

        
            if(mb_strlen($_POST['password'],'UTF-8')>= 12){

                if($_POST['password'] == $_POST['dumypassword'])
                
                $prenom = trim($_POST['prenom']);
                $nom = trim($_POST['nom']);
                $email = trim($_POST['email']);

                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                try{
                    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                    $request = $pdo->prepare('SELECT m.prenom, m.nom, m.email FROM membre m WHERE m.email = ?');

                    $request->bindParam(1,$email,PDO::PARAM_STR);

                    $request->execute();

                    $data = $request->fetch(PDO::FETCH_ASSOC);

                    if(empty($data)){
                        $request = $pdo->prepare('INSERT INTO membre (prenom, nom, email, mot_de_passe) VALUES(?, ?, ?, ?)');

                        $request->bindParam(1,$prenom,PDO::PARAM_STR);
                        $request->bindParam(2,$nom,PDO::PARAM_STR);
                        $request->bindParam(3,$email,PDO::PARAM_STR);
                        $request->bindParam(4,$password,PDO::PARAM_STR);

                        $request->execute();

                        $message = "L'utilisateur a été enregistré :  {$prenom} {$nom} - {$email}";

                    }else{
                        $message = "Cette email est déjà utilisé par un autre compte.";
                    }

                }catch(EXCEPTION $error){
                    die($error->getMessage());
                }
                }else{
                    $message = "Le mot de passe n'est pas identique à sa confirmation";
                }
            }else{
                $message = "Votre mot de passe doit faire au moins 12 caractères.";
            }

        }else{
            $message = "Votre email n'est pas au bon format.";
        }

    }else{
        $message = "Veuillez remplir tous les champs du formulaire.";
    }


?>
 <main>
        <h1>Page d'Accueil</h1>
        <?php 
            if(!isset($_SESSION['id'])){
        ?>
                <section>
                    <h2>Inscription</h2>
                    <form action="" method="post">
                        <label for="prenom">Prenom <input type="text" name="prenom" id="prenom"></label>
                        <label for="nom">Nom <input type="text" name="nom" id="nom"></label>
                        <label for="email">Email <input type="text" name="email" id="email"></label>
                        <label for="password">Mot de Passe <input type="text" name="password" id="password"></label>
                        <label for="dumypassword">Confirmation du Mot de Passe <input type="text" name="dumypassword" id="dumypassword"></label>
                        <input type="submit" name="submit">
                    </form>
                    <p>
                        <?php echo($message) ?>
                    </p>
                </section>
</main>
<?php } ?>