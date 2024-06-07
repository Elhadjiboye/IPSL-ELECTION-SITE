<?php
    include 'includes/session.php';

    if(isset($_POST['delete'])){
        // Récupérer l'ID de l'élément à supprimer
        $id = $_POST['id'];
        echo "ID récupéré pour la suppression : " . $id; // Débogage

        // Exécuter la requête de suppression
        $sql = "DELETE FROM election WHERE id_election = '$id'";
        if($conn->query($sql)){
            $_SESSION['success'] = 'Election supprimée avec succès';
        }
        else{
            $_SESSION['error'] = $conn->error;
        }
    }
    else{
        $_SESSION['error'] = 'Sélectionnez d\'abord l\'élément à supprimer.';
    }

    header('location: positions.php');
?>
