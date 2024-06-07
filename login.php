
<?php
session_start();
include 'includes/conn.php';

define('DEFAULT_PASSWORD', 'password');

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $mail_saisi = $_POST["voter"];
    $mot_de_passe_saisi = $_POST["password"];

    // Stockage dans les variables de session
    $_SESSION["voter"] = $mail_saisi;
    $_SESSION["password"] = $mot_de_passe_saisi;

    // Vérification de l'existence des variables de session
    if (isset($_SESSION["voter"]) && isset($_SESSION["password"])) {
        // Récupération des données stockées dans les variables de session
        $mail_saisi = $_SESSION["voter"];
        $mot_de_passe_saisi = $_SESSION["password"];
        

        // Vérification des identifiants dans la base de données
        $stmt = $conn->prepare(SELECT * FROM electeur WHERE mail_electeur = ? AND mot_de_passe_electeur = ?
	);
        $stmt->bindParam(':mail', $mail_saisi);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe_saisi);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($stmt->rowCount() > 0) {
            
                // Vérifier si le mot de passe par défaut est utilisé
                if ($user['mot_de_passe_electeur'] == DEFAULT_PASSWORD) {
                    // Afficher le formulaire de réinitialisation du mot de passe
                    header("Location: traitement_reinitialisation.php");
                    exit();
                    // Assurez-vous que ce formulaire envoie les données à un script de traitement pour mettre à jour le mot de passe dans la base de données
                    // Vous pouvez utiliser JavaScript pour afficher ou masquer ce formulaire en fonction de la condition
                } else {
                    // Redirection vers la page sécurisée pour les utilisateurs normaux
                    header("Location: home.php");
                    exit();
                }
            }
        } else {
            // Identifiants incorrects, rester sur la page de connexion
            $error_message = "Identifiants incorrects. Veuillez réessayer.";
        }
    } else {
        // Les variables de session ne sont pas définies, rester sur la page de formulaire
        $error_message = "Une erreur s'est produite. Veuillez réessayer.";
    }
    header('location: home.php');

?>