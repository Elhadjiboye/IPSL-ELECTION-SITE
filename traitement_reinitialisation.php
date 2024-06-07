<?php
session_start();
include 'includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nouveau_mot_de_passe = $_POST["nouveau_mot_de_passe"];
    $confirmation_mot_de_passe = $_POST["confirmation_mot_de_passe"];

    // Vérifier si les champs ne sont pas vides
    if (!empty($nouveau_mot_de_passe) && !empty($confirmation_mot_de_passe)) {
        // Vérifier si les mots de passe correspondent
        if ($nouveau_mot_de_passe === $confirmation_mot_de_passe) {
            // Récupérer l'adresse e-mail de l'utilisateur à partir de la session
            $id_utilisateurs= $_SESSION["voter"];
            
            // Rechercher l'identifiant de l'utilisateur à partir de l'adresse e-mail dans la base de données
            $stmt = $conn->prepare("SELECT id_electeur, mot_de_passe_electeur FROM electeur WHERE id_electeur = ?");
            $stmt->bind_param("s", $id_utilisateurs);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $id_utilisateur = $user['id_electeur'];
                $ancien_mot_de_passe = $user['mot_de_passe_electeur'];

                // Vérifier si le nouveau mot de passe est identique à l'ancien mot de passe
                if ($nouveau_mot_de_passe === $ancien_mot_de_passe) {
                    // Afficher un message d'erreur
                    $error_message = "Le nouveau mot de passe doit être différent de l'ancien mot de passe.";
                } else {
                    // Préparer et exécuter la requête de mise à jour du mot de passe
                    $stmt = $conn->prepare("UPDATE electeur SET mot_de_passe_electeur = ? WHERE id_electeur = ?");
                    $stmt->bind_param("si", $nouveau_mot_de_passe, $id_utilisateur);
                    $stmt->execute();

                    // Vérifier les erreurs MySQL
                    if ($stmt->errno) {
                        $_SESSION['error'] = "Erreur MySQL: " . $stmt->error;
                    } else {
                        // Afficher un message de confirmation
                        $success_message = "Le mot de passe a été réinitialisé avec succès.";

                        // Rediriger vers la page de connexion
                        header("Location: login-page.php");
                        exit();
                    }
                }
            } else {
                // Si aucun utilisateur n'est trouvé avec l'adresse e-mail fournie
                $error_message = "Aucun utilisateur trouvé avec cette adresse e-mail.";
            }
        } else {
            // Si les mots de passe ne correspondent pas
            $error_message = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
        }
    } else {
        // Si les champs de mot de passe sont vides
        $error_message = "Les champs de mot de passe ne peuvent pas être vides.";
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
            <p class="login-box-msg">Réinitialisation du mot de passe</p>

            <form method="POST">
                <div class="form-group has-feedback">
                    <input type="password" class="form-control login-input" name="nouveau_mot_de_passe" placeholder="Nouveau mot de passe" required>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control login-input" name="confirmation_mot_de_passe" placeholder="Confirmer le mot de passe" required>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat login-btn" name="login">Réinitialiser</button>
                    </div>
                </div>
            </form>
            <?php
                if(isset($error_message)){
                    echo "
                        <div class='callout callout-danger text-center mt20'>
                            <p>".$error_message."</p> 
                        </div>
                    ";
                }
            ?>
        </div>
    </div>
    
    <?php include 'includes/scripts.php' ?>

</html>
