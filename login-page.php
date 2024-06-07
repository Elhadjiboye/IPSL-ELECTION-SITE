<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/conn.php';

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $voter= $_POST["voter"];
    $mot_de_passe_saisi = $_POST["password"];

    // Stockage dans les variables de session
    $_SESSION["voter"] = $voter;
    $_SESSION["mot_de_passe"] = $mot_de_passe_saisi;

    // Vérification de l'existence des variables de session
    if (isset($_SESSION["voter"]) && isset($_SESSION["mot_de_passe"])) {
        // Récupération des données stockées dans les variables de session
        $voter= $_SESSION["voter"];
        $mot_de_passe_saisi = $_SESSION["mot_de_passe"];

        // Vérification des identifiants dans la base de données
        $stmt = $conn->prepare("SELECT * FROM electeur WHERE id_electeur = ? LIMIT 1");
        $stmt->bind_param("s", $voter);
        $stmt->execute();
        $result = $stmt->get_result(); // Récupération des résultats de la requête

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc(); // Récupération des données de l'utilisateur

            // Vérification du mot de passe
            if ($mot_de_passe_saisi == $user['mot_de_passe_electeur']) {
                if ($user['mot_de_passe_electeur'] == 'password') {
                    // Mot de passe par défaut, rediriger vers la page de changement de mot de passe
                    header("Location: traitement_reinitialisation.php");
                    exit();
                } else {
                    // Mot de passe correct, stocker l'identifiant de l'utilisateur dans la session
                    $_SESSION['voter'] = $user['id_electeur']; 
                    // Rediriger vers la page d'accueil
                    header("Location: home.php");
                    exit();
                }
            } else {
                // Mot de passe incorrect, définir le message d'erreur approprié
                $_SESSION['error'] = "Mot de passe incorrect. Veuillez réessayer.";
            }
        } else {
            // Utilisateur non trouvé, définir le message d'erreur approprié
            $_SESSION['error'] = "id incorrecte. Veuillez réessayer.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Smart-Vote System</title>

    <?php include 'includes/header.php'; ?>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>Smart-Vote System</b>
        </div>
    
        <div class="login-box-body">
            <p class="login-box-msg">Connectez-vous pour voter !</p>

            <form method="POST">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control login-input" name="voter" placeholder="id Electeur" required>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control login-input" name="password" placeholder="Password" required>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat login-btn" name="login">Connexion</button>
                    </div>
                </div>
            </form>
        </div>
        <?php
            if(isset($_SESSION['error'])){
                echo "
                    <div class='callout callout-danger text-center mt20'>
                        <p>".$_SESSION['error']."</p> 
                    </div>
                ";
                unset($_SESSION['error']); // Supprimer le message d'erreur après l'avoir affiché
            }
        ?>
    </div>
    
    <?php include 'includes/scripts.php' ?>
    
</body>
</html>
