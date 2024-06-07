<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$prenom = $_POST['prenom_candidat'];
		$nom = $_POST['nom_candidat'];
		$election = $_POST['election'];

		$sql = "UPDATE candidat SET prenom_candidat = '$prenom', nom_candidat = '$nom', id_election = '$election', programme_detude = '$programme' WHERE id_candidat = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Le candidat a été mis à jour avec succès.';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Veuillez remplir d\'abord le formulaire de modification.';
	}

	header('location: candidates.php');

?>