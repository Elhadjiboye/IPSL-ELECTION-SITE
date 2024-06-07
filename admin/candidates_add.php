<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$prenom = $_POST['prenom_candidat'];
		$nom = $_POST['nom_candidat'];
		$mail = $_POST['mail_candidat'];
		$programme = $_POST['programme d\'étude'];
		$election = $_POST['election'];
		$photo = $_FILES['photo']['name'];
		if(!empty($photo)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);	
		}

		$sql = "INSERT INTO candidat (nom_candidat, prenom_candidat, mail_candidat,programme_detude,id_election, photo) VALUES ('$nom', '$prenom', '$mail','$programme', '$election','$photo')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Candidat ajouté avec succès.';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Remplissez d\'abord le formulaire d\'ajout.';
	}

	header('location: candidates.php');
?>