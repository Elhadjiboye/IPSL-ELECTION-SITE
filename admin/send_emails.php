<?php
include 'includes/conn.php';

// Vérifier si les e-mails ont déjà été envoyés
if (!isset($_SESSION['emails_sent'])) {
    // Récupérer les informations des électeurs depuis la base de données
    $sql = "SELECT id_electeur, nom_electeur, prenom_electeur, mail_electeur FROM electeur";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Configuration de l'e-mail
        $subject = "Informations de connexion pour l'élection";
        $message = "Bonjour [NOM_ELECTEUR],\n\n";
        $message .= "Voici vos informations de connexion pour l'élection :\n\n";
        $message .= "Titre de l'élection : [TITRE_ELECTION]\n";
        $message .= "Identifiant : [ID_ELECTEUR]\n";
        $message .= "Mot de passe : password\n\n";
        $message .= "Vous pouvez maintenant vous connecter avec ces informations sur notre site.\n";
        $message .= "Merci.";

        while($row = $result->fetch_assoc()) {
            // Remplacer les balises par les valeurs réelles
            $mail_content = str_replace('[NOM_ELECTEUR]', $row['prenom_electeur'], $message);
            $mail_content = str_replace('[TITRE_ELECTION]', "Titre de votre élection", $mail_content);
            $mail_content = str_replace('[ID_ELECTEUR]', $row['id_electeur'], $mail_content);

            // Envoyer l'e-mail à chaque électeur en utilisant MailHog
            $to = $row['mail_electeur'];
            $headers = "From: boyeelhadjiabdou@gmail.com\r\n"; // Mettez votre adresse e-mail ici
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            $headers .= "X-Priority: 1\r\n"; // Priorité du message

            // Configuration SMTP pour utiliser MailHog
            ini_set("SMTP", "localhost");
            ini_set("smtp_port", "1025");

            // Envoyer l'e-mail
            mail($to, $subject, $mail_content, $headers);
        }

        // Définir une session pour indiquer que les e-mails ont été envoyés
        $_SESSION['emails_sent'] = true;
        
        // Définir un message de confirmation
        $_SESSION['success'] = 'Les e-mails ont été envoyés avec succès.';
    } else {
        $_SESSION['error'] = 'Aucun électeur trouvé dans la base de données.';
    }
}

// Redirection vers la page précédente seulement si les e-mails ont été envoyés avec succès
if (isset($_SESSION['success'])) {
    header('Location: voters.php');
    exit;
}
?>
